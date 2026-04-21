<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\AlternatifLegalitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
}
