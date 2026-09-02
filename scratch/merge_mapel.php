<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

DB::beginTransaction();

try {
    $mapels = DB::table('mapel')->get();

    // Mapping new code to new name
    $newMapels = [];
    $oldToNewCode = [];

    foreach ($mapels as $m) {
        $name = trim($m->nama_mapel);
        
        // Fix typos
        if (str_contains($name, 'Kreativitas')) {
            $name = 'Kreativitas, Inovasi, dan Kewirausahaan';
        }

        // Generate a new code based on initials, up to 10 chars
        $words = explode(' ', str_replace(',', '', $name));
        $code = '';
        if (count($words) == 1) {
            $code = strtoupper(substr($name, 0, 4));
        } else {
            foreach ($words as $w) {
                if (!empty($w) && strtolower($w) != 'dan') {
                    $code .= strtoupper(substr($w, 0, 1));
                }
            }
        }

        // Handle specific cases to match existing clean codes if possible
        if ($name == 'Bahasa Indonesia') $code = 'BIND';
        if ($name == 'Bahasa Inggris') $code = 'BING';
        if ($name == 'Bahasa Jawa') $code = 'BJAWA';
        if ($name == 'Bahasa Jepang') $code = 'BJEP';
        if ($name == 'Seni Budaya') $code = 'SBUD';
        if ($name == 'Pendidikan Agama Islam dan Budi Pekerti') $code = 'PAIBP';
        if ($name == 'Pendidikan Pancasila') $code = 'PPKN';

        $code = substr($code, 0, 10);

        // Save mapping
        $newMapels[$code] = $name;
        $oldToNewCode[$m->kode_mapel] = $code;
    }

    // Insert new mapels if they don't exist yet
    foreach ($newMapels as $code => $name) {
        $exists = DB::table('mapel')->where('kode_mapel', $code)->exists();
        if (!$exists) {
            DB::table('mapel')->insert([
                'kode_mapel' => $code,
                'nama_mapel' => $name,
            ]);
        } else {
            // Update name just in case it was a typo before
            DB::table('mapel')->where('kode_mapel', $code)->update(['nama_mapel' => $name]);
        }
    }

    // Update jadwal and guru
    foreach ($oldToNewCode as $old => $new) {
        if ($old !== $new) {
            DB::table('jadwal')->where('kode_mapel', $old)->update(['kode_mapel' => $new]);
            DB::table('guru')->where('kode_mapel', $old)->update(['kode_mapel' => $new]);
        }
    }

    // Delete old mapels that are not in the new set
    $newCodes = array_keys($newMapels);
    DB::table('mapel')->whereNotIn('kode_mapel', $newCodes)->delete();

    DB::commit();
    echo "Sukses merapikan mapel!\n";
    echo "Jumlah Mapel Unik: " . count($newMapels) . "\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
