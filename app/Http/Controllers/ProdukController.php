<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\AlternatifLegalitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


class ProdukController extends Controller
{
    public function index()
    {
        $produk = Alternatif::with('legalitas')->get();
        return view('admin.produk', compact('produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:150',
            'nama_brand_umkm' => 'required|string|max:150',
            'nama_pemilik' => 'required|string|max:150',
            'deskripsi_produk' => 'nullable|string',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except('foto_produk');
            $data['is_aktif'] = false; // Default: Legalitas belum diisi
            
            if ($request->hasFile('foto_produk')) {
                $file = $request->file('foto_produk');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('produk', $filename, 'public');
                $data['foto_produk'] = $path;
            }

            $produk = Alternatif::create($data);

            // Create default legalitas record
            AlternatifLegalitas::create([
                'id_alternatif' => $produk->id_alternatif,
                'is_nib' => false,
                'is_bpom' => false,
                'is_sp_pirt' => false,
                'is_sertifikat_halal' => false,
                'lolos_filter' => false,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pemilik' => 'required|string|max:150',
            'deskripsi_produk' => 'nullable|string',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $produk = Alternatif::findOrFail($id);
        $data = $request->except('foto_produk');

        if ($request->hasFile('foto_produk')) {
            // Delete old photo if exists
            if ($produk->foto_produk) {
                Storage::disk('public')->delete($produk->foto_produk);
            }

            $file = $request->file('foto_produk');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('produk', $filename, 'public');
            $data['foto_produk'] = $path;
        }

        $produk->update($data);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $produk = Alternatif::findOrFail($id);
        
        if ($produk->foto_produk) {
            Storage::disk('public')->delete($produk->foto_produk);
        }

        $produk->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }

    public function updateLegalitas(Request $request, $id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'is_nib' => 'required|boolean',
            'no_nib' => 'required_if:is_nib,1|nullable|numeric|digits:13',
            'is_bpom' => 'required|boolean',
            'bpom_type' => 'required_if:is_bpom,1|nullable|in:MD,ML',
            'no_bpom' => 'required_if:is_bpom,1|nullable|numeric|digits:12',
            'is_sp_pirt' => 'required|boolean',
            'no_sp_pirt_1' => 'required_if:is_sp_pirt,1|nullable|numeric|digits:13',
            'no_sp_pirt_2' => 'required_if:is_sp_pirt,1|nullable|numeric|digits:2',
            'is_sertifikat_halal' => 'required|boolean',
            'no_sertifikat_halal' => 'required_if:is_sertifikat_halal,1|nullable|numeric|digits:13',
            'keterangan' => 'nullable|string',
        ], [
            'required_if' => 'Nomor dokumen wajib diisi jika status tersedia.',
            'digits' => 'Nomor :attribute harus berjumlah :digits angka.',
            'numeric' => 'Nomor :attribute harus berupa angka.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_id', $id);
        }

        $legalitas = \App\Models\AlternatifLegalitas::where('id_alternatif', $id)->firstOrFail();
        
        $data = $request->except(['no_sp_pirt_1', 'no_sp_pirt_2']);

        // 1. Format Sertifikat Halal: Input 13 digits -> Store IDXXXXXXXXXXXXX
        if ($request->is_sertifikat_halal && $request->no_sertifikat_halal) {
            $data['no_sertifikat_halal'] = 'ID' . $request->no_sertifikat_halal;
        }

        // 2. Format SP-PIRT: Input 13 + 2 digits -> Store XXXXXXXXXXXXX-XX
        if ($request->is_sp_pirt && $request->no_sp_pirt_1 && $request->no_sp_pirt_2) {
            $data['no_sp_pirt'] = $request->no_sp_pirt_1 . '-' . $request->no_sp_pirt_2;
        }

        // 3. Format BPOM: Type (MD/ML) + 12 digits -> Store BPOM RI [TYPE] XXXXXXXXXXXX
        if ($request->is_bpom && $request->no_bpom) {
            $data['no_bpom'] = 'BPOM RI ' . $request->bpom_type . ' ' . $request->no_bpom;
        }

        // Calculate lolos_filter
        $lolos = $request->is_nib && 
                 $request->is_sertifikat_halal && 
                 ($request->is_bpom || $request->is_sp_pirt);

        $legalitas->update(array_merge($data, ['lolos_filter' => $lolos]));

        // Repurpose is_aktif: Menandakan bahwa data legalitas sudah pernah diisi/disimpan
        $legalitas->alternatif->update(['is_aktif' => true]);

        return redirect()->back()->with('success', 'Legalitas produk berhasil diperbarui.');
    }
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        
        // Sheet 1: Detail Produk
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Detail Produk');
        $sheet1->setCellValue('A1', 'Nama Produk');
        $sheet1->setCellValue('B1', 'Nama Brand UMKM');
        $sheet1->setCellValue('C1', 'Nama Pemilik');
        $sheet1->setCellValue('D1', 'Deskripsi Produk');
        
        // Style Header Sheet 1
        $sheet1->getStyle('A1:D1')->getFont()->setBold(true);
        foreach(range('A','D') as $columnID) {
            $sheet1->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Sheet 2: Legalitas
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Legalitas');
        $sheet2->setCellValue('A1', 'Nama Produk');
        $sheet2->setCellValue('B1', 'Nama Brand UMKM');
        $sheet2->setCellValue('C1', 'NIB (Ya/Tidak)');
        $sheet2->setCellValue('D1', 'No NIB');
        $sheet2->setCellValue('E1', 'BPOM (Ya/Tidak)');
        $sheet2->setCellValue('F1', 'Tipe BPOM (MD/ML)');
        $sheet2->setCellValue('G1', 'No BPOM');
        $sheet2->setCellValue('H1', 'SP-PIRT (Ya/Tidak)');
        $sheet2->setCellValue('I1', 'No SP-PIRT (15 digit)');
        $sheet2->setCellValue('J1', 'Halal (Ya/Tidak)');
        $sheet2->setCellValue('K1', 'No Halal (13 digit)');
        $sheet2->setCellValue('L1', 'Keterangan');

        // Formulasi otomatis dari sheet pertama (untuk 100 baris pertama)
        for ($i = 2; $i <= 101; $i++) {
            $sheet2->setCellValue("A$i", "='Detail Produk'!A$i");
            $sheet2->setCellValue("B$i", "='Detail Produk'!B$i");
        }

        // Style Header Sheet 2
        $sheet2->getStyle('A1:L1')->getFont()->setBold(true);
        foreach(range('A','L') as $columnID) {
            $sheet2->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_import_produk.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file_excel');
        
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }
        
        DB::beginTransaction();
        try {
            // 1. Process Sheet 1: Detail Produk
            $sheet1 = $spreadsheet->getSheetByName('Detail Produk');
            if (!$sheet1) {
                throw new \Exception('Sheet "Detail Produk" tidak ditemukan.');
            }

            $rows1 = $sheet1->toArray();
            $header1 = array_shift($rows1); // Remove header

            foreach ($rows1 as $row) {
                if (empty($row[0]) || empty($row[1])) continue; // Skip empty nama_produk or brand

                $nama_produk = trim($row[0]);
                $brand = trim($row[1]);
                $pemilik = $row[2] ?? '';
                $deskripsi = $row[3] ?? null;

                $produk = Alternatif::where('nama_produk', $nama_produk)
                    ->where('nama_brand_umkm', $brand)
                    ->first();

                if (!$produk) {
                    $produk = Alternatif::create([
                        'nama_produk' => $nama_produk,
                        'nama_brand_umkm' => $brand,
                        'nama_pemilik' => $pemilik,
                        'deskripsi_produk' => $deskripsi,
                        'is_aktif' => false // Default: Belum diisi legalitas
                    ]);
                } else {
                    $produk->update([
                        'nama_pemilik' => $pemilik,
                        'deskripsi_produk' => $deskripsi
                    ]);
                }

                // Ensure legalitas record exists
                if (!$produk->legalitas) {
                    AlternatifLegalitas::create([
                        'id_alternatif' => $produk->id_alternatif,
                    ]);
                }
            }

            // 2. Process Sheet 2: Legalitas
            $sheet2 = $spreadsheet->getSheetByName('Legalitas');
            if ($sheet1) { // We mainly need sheet 1 to have processed first
                if ($sheet2) {
                    $rows2 = $sheet2->toArray();
                    $header2 = array_shift($rows2);

                    foreach ($rows2 as $row) {
                        if (empty($row[0]) || empty($row[1])) continue;

                        $nama_produk = trim($row[0]);
                        $brand = trim($row[1]);

                        $produk = Alternatif::where('nama_produk', $nama_produk)
                            ->where('nama_brand_umkm', $brand)
                            ->first();

                        if ($produk) {
                            $legalitas = $produk->legalitas;
                            
                            $is_nib = (strtolower(trim($row[2] ?? '')) === 'ya' || trim($row[2] ?? '') == '1');
                            $no_nib = trim($row[3] ?? '') ?: null;
                            
                            $is_bpom = (strtolower(trim($row[4] ?? '')) === 'ya' || trim($row[4] ?? '') == '1');
                            $bpom_type = strtoupper(trim($row[5] ?? ''));
                            $no_bpom_raw = trim($row[6] ?? '');
                            
                            $is_pirt = (strtolower(trim($row[7] ?? '')) === 'ya' || trim($row[7] ?? '') == '1');
                            $no_pirt_raw = trim($row[8] ?? '');
                            
                            $is_halal = (strtolower(trim($row[9] ?? '')) === 'ya' || trim($row[9] ?? '') == '1');
                            $no_halal_raw = trim($row[10] ?? '');
                            
                            $keterangan = trim($row[11] ?? '') ?: null;

                            $updateData = [
                                'is_nib' => $is_nib,
                                'no_nib' => $no_nib,
                                'is_bpom' => $is_bpom,
                                'is_sp_pirt' => $is_pirt,
                                'is_sertifikat_halal' => $is_halal,
                                'keterangan' => $keterangan,
                            ];

                            // BPOM Formatting
                            if ($is_bpom && $bpom_type && $no_bpom_raw) {
                                $updateData['no_bpom'] = 'BPOM RI ' . $bpom_type . ' ' . $no_bpom_raw;
                            } else {
                                $updateData['no_bpom'] = $no_bpom_raw ?: null;
                            }

                            // Halal Formatting
                            if ($is_halal && $no_halal_raw) {
                                $updateData['no_sertifikat_halal'] = 'ID' . $no_halal_raw;
                            } else {
                                $updateData['no_sertifikat_halal'] = $no_halal_raw ?: null;
                            }

                            // PIRT Formatting (Split 15 digit to 13-2)
                            if ($is_pirt && strlen($no_pirt_raw) === 15) {
                                $updateData['no_sp_pirt'] = substr($no_pirt_raw, 0, 13) . '-' . substr($no_pirt_raw, 13, 2);
                            } elseif ($is_pirt && $no_pirt_raw) {
                                $updateData['no_sp_pirt'] = $no_pirt_raw;
                            } else {
                                $updateData['no_sp_pirt'] = null;
                            }

                            // Calculate lolos_filter
                            $lolos = $is_nib && $is_halal && ($is_bpom || $is_pirt);
                            $updateData['lolos_filter'] = $lolos;

                            $legalitas->update($updateData);
                            $produk->update(['is_aktif' => true]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data produk berhasil diimport.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
