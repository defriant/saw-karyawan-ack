<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\NKriteria;
use App\Models\NPenilaian;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WebController extends Controller
{
    function random($type, $length)
    {
        $result = "";
        if ($type == 'char') {
            $char = 'ABCDEFGHJKLMNPRTUVWXYZ';
            $max        = strlen($char) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = mt_rand(0, $max);
                $result .= $char[$rand];
            }
            return $result;
        } elseif ($type == 'num') {
            $char = '123456789';
            $max        = strlen($char) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = mt_rand(0, $max);
                $result .= $char[$rand];
            }
            return $result;
        } elseif ($type == 'mix') {
            $char = 'ABCDEFGHJKLMNPRTUVWXYZ123456789';
            $max = strlen($char) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = mt_rand(0, $max);
                $result .= $char[$rand];
            }
            return $result;
        }
    }

    public function login_attempt(Request $request)
    {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect('/dashboard');
        } else {
            Session::flash('failed');
            return redirect()->back()->withInput($request->all());
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function dashboard()
    {
        $karyawan = Karyawan::all();
        $karyawan = count($karyawan);

        $kriteria = Kriteria::all();
        $kriteria = count($kriteria);

        return view('dashboard', compact('karyawan', 'kriteria'));
    }

    public function get_data_karyawan()
    {
        $karyawan = Karyawan::all();
        $response = [
            "data" => $karyawan,
            "response" => "success",
        ];
        return response()->json($response);
    }

    public function input_data_karyawan(Request $request)
    {
        $idKaryawan = 'K' . $this->random('num', 4);
        while (true) {
            $cek = Karyawan::where('id', $idKaryawan)->first();
            if ($cek) {
                $idKaryawan = 'K' . $this->random('num', 4);
            } else {
                break;
            }
        }

        Karyawan::create([
            'id' => $idKaryawan,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat
        ]);

        $penilaian = Penilaian::all();
        $kriteria = Kriteria::all();

        $periodePenilaian = [];
        foreach ($penilaian as $p) {
            $cek = array_search($p->periode, $periodePenilaian);
            if ($cek === false) {
                $periodePenilaian[] = $p->periode;
            }
        }

        foreach ($periodePenilaian as $periode) {
            foreach ($kriteria as $k) {
                Penilaian::create([
                    'periode' => $periode,
                    'id_karyawan' => $idKaryawan,
                    'id_kriteria' => $k->id,
                    'nilai' => 0
                ]);
            }

            $npenilaian = NPenilaian::where('periode', $periode)->get();
            foreach ($npenilaian as $np) {
                NPenilaian::where('id', $np->id)->delete();
            }

            $penilaian = Penilaian::where('periode', $periode)->get();
            foreach ($penilaian as $p) {
                $maxVal = Penilaian::where('periode', $periode)->where('id_kriteria', $p->id_kriteria)->max('nilai');
                $nilai = 0;
                if ($maxVal > 0) {
                    $nilai = $p->nilai / $maxVal;
                    $nilai = number_format((float)$nilai, 2, '.', '');
                }

                $np_id = NPenilaian::max('id');
                $np_id = $np_id + 1;
                NPenilaian::create([
                    'id' => $np_id,
                    'periode' => $p->periode,
                    'id_karyawan' => $p->id_karyawan,
                    'id_kriteria' => $p->id_kriteria,
                    'nilai' => $nilai
                ]);
            }
        }

        $response = [
            "response" => "success",
            "message" => "Data karyawan berhasil ditambahkan"
        ];
        return response()->json($response);
    }

    public function update_data_karyawan(Request $request)
    {
        Karyawan::where('id', $request->id)->update([
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat
        ]);

        $response = [
            "response" => "success",
            "message" => $request->nama . " berhasil di update"
        ];

        return response()->json($response);
    }

    public function delete_data_karyawan(Request $request)
    {
        Karyawan::where('id', $request->id)->delete();
        Penilaian::where('id_karyawan', $request->id)->delete();
        NPenilaian::where('id_karyawan', $request->id)->delete();

        $response = [
            "response" => "success",
            "message" => "Berhasil menghapus data karyawan"
        ];
        return response()->json($response);
    }

    public function get_kriteria()
    {
        $kriteria = Kriteria::all();
        $response = [
            "data" => $kriteria,
            "response" => "success"
        ];

        return response()->json($response);
    }

    public function add_kriteria(Request $request)
    {
        $idKriteriaMax = Kriteria::all();
        $idKriteriaMax = count($idKriteriaMax) + 1;
        $idKriteria = $idKriteriaMax . $this->random('mix', 4);
        while (true) {
            $cek = Kriteria::where('id', $idKriteria)->first();
            if ($cek) {
                $idKriteriaMax = Kriteria::all();
                $idKriteriaMax = count($idKriteriaMax) + 1;
                $idKriteria = $idKriteriaMax . $this->random('mix', 4);
            } else {
                break;
            }
        }

        Kriteria::create([
            'id' => $idKriteria,
            'nama' => $request->nama,
            'bobot' => $request->bobot
        ]);

        $penilaian = Penilaian::all();
        $karyawan = Karyawan::all();

        $periodePenilaian = [];
        foreach ($penilaian as $p) {
            $cek = array_search($p->periode, $periodePenilaian);
            if ($cek === false) {
                $periodePenilaian[] = $p->periode;
            }
        }

        foreach ($periodePenilaian as $periode) {
            foreach ($karyawan as $k) {
                Penilaian::create([
                    'periode' => $periode,
                    'id_karyawan' => $k->id,
                    'id_kriteria' => $idKriteria,
                    'nilai' => 0
                ]);
            }
        }

        $nkriteria = NKriteria::all();
        foreach ($nkriteria as $nk) {
            NKriteria::where('id', $nk->id)->delete();
        }

        $maxBobot = Kriteria::sum('bobot');
        $kriteria = Kriteria::all();
        foreach ($kriteria as $k) {
            $bobot = $k->bobot / $maxBobot;
            $bobot = number_format((float)$bobot, 2, '.', '');

            NKriteria::create([
                'id' => $k->id,
                'nama' => $k->nama,
                'bobot' => $bobot
            ]);
        }

        return response()->json(["response" => "success"]);
    }

    public function update_kriteria(Request $request)
    {
        Kriteria::where('id', $request->id)->update([
            "nama" => $request->nama,
            "bobot" => $request->bobot
        ]);

        $nkriteria = NKriteria::all();
        foreach ($nkriteria as $nk) {
            NKriteria::where('id', $nk->id)->delete();
        }

        $maxBobot = Kriteria::sum('bobot');
        $kriteria = Kriteria::all();
        foreach ($kriteria as $k) {
            $bobot = $k->bobot / $maxBobot;
            $bobot = number_format((float)$bobot, 2, '.', '');

            NKriteria::create([
                'id' => $k->id,
                'nama' => $k->nama,
                'bobot' => $bobot
            ]);
        }

        return response()->json(["response" => "success"]);
    }

    public function delete_kriteria(Request $request)
    {
        Kriteria::where('id', $request->id)->delete();
        Penilaian::where('id_kriteria', $request->id)->delete();

        $nkriteria = NKriteria::all();
        foreach ($nkriteria as $nk) {
            NKriteria::where('id', $nk->id)->delete();
        }

        $maxBobot = Kriteria::sum('bobot');
        $kriteria = Kriteria::all();
        foreach ($kriteria as $k) {
            $bobot = $k->bobot / $maxBobot;
            $bobot = number_format((float)$bobot, 2, '.', '');

            NKriteria::create([
                'id' => $k->id,
                'nama' => $k->nama,
                'bobot' => $bobot
            ]);
        }

        return response()->json(["response" => "success"]);
    }

    public function get_kriteria_normalisasi()
    {
        $nkriteria = NKriteria::all();
        return response()->json($nkriteria);
    }

    public function get_sub_kriteria()
    {
        $SubKriteria = SubKriteria::all();
        $response = [
            "data" => $SubKriteria,
            "response" => "success"
        ];

        return response()->json($response);
    }

    public function add_sub_kriteria(Request $request)
    {
        $idKriteriaMax = SubKriteria::all();
        $idKriteriaMax = count($idKriteriaMax) + 1;
        $idKriteria = $idKriteriaMax . $this->random('mix', 4);
        while (true) {
            $cek = SubKriteria::where('id', $idKriteria)->first();
            if ($cek) {
                $idKriteriaMax = SubKriteria::all();
                $idKriteriaMax = count($idKriteriaMax) + 1;
                $idKriteria = $idKriteriaMax . $this->random('mix', 4);
            } else {
                break;
            }
        }

        SubKriteria::create([
            'id' => $idKriteria,
            'nama' => $request->nama,
            'nilai' => $request->nilai
        ]);

        return response()->json(["response" => "success"]);
    }

    public function update_sub_kriteria(Request $request)
    {
        SubKriteria::where('id', $request->id)->update([
            "nama" => $request->nama,
            "nilai" => $request->nilai
        ]);
        return response()->json(["response" => "success"]);
    }

    public function delete_sub_kriteria(Request $request)
    {
        SubKriteria::where('id', $request->id)->delete();

        return response()->json(["response" => "success"]);
    }

    public function get_penilaian_karyawan(Request $request)
    {
        $penilaian = Penilaian::where('periode', $request->periode)->get();
        if (count($penilaian) == 0) {
            return response()->json(["response" => false, "periode" => $request->periode]);
        } else {
            $karyawan = Karyawan::all();
            $kriteria = Kriteria::all();
            $data = [];

            foreach ($karyawan as $karyawanKey => $karyawanVal) {
                $data[$karyawanKey]["id_karyawan"] = $karyawanVal->id;
                $data[$karyawanKey]["nama"] = $karyawanVal->nama;

                foreach ($kriteria as $kriteriaKey => $kriteriaVal) {
                    foreach ($penilaian as $pKey => $pVal) {
                        if ($penilaian[$pKey]["id_karyawan"] == $karyawanVal->id && $penilaian[$pKey]["id_kriteria"] == $kriteriaVal->id) {
                            $data[$karyawanKey]["nilai"][] = [
                                "kriteria" => $penilaian[$pKey]["id_kriteria"],
                                "nilai" => $penilaian[$pKey]["nilai"]
                            ];
                        }
                    }
                }
            }

            $subKriteria = SubKriteria::all();

            $response = [
                "response" => true,
                "periode" => $request->periode,
                "data" => $data,
                "subKriteria" => $subKriteria
            ];
            return response()->json($response);
        }
    }

    public function create_penilaian_karyawan(Request $request)
    {
        $kriteria = Kriteria::all();
        $karyawan = Karyawan::all();

        foreach ($kriteria as $kriteriaVal) {
            foreach ($karyawan as $karyawanVal) {
                Penilaian::create([
                    'periode' => $request->periode,
                    'id_karyawan' => $karyawanVal->id,
                    'id_kriteria' => $kriteriaVal->id,
                    'nilai' => 0
                ]);
            }
        }

        $npenilaian = NPenilaian::where('periode', $request->periode)->get();
        foreach ($npenilaian as $np) {
            NPenilaian::where('id', $np->id)->delete();
        }

        $penilaian = Penilaian::where('periode', $request->periode)->get();

        foreach ($penilaian as $p) {
            $maxVal = Penilaian::where('periode', $request->periode)->where('id_kriteria', $p->id_kriteria)->max('nilai');
            $nilai = 0;
            if ($maxVal > 0) {
                $nilai = $p->nilai / $maxVal;
                $nilai = number_format((float)$nilai, 2, '.', '');
            }
            $np_id = NPenilaian::max('id');
            $np_id = $np_id + 1;
            NPenilaian::create([
                'id' => $np_id,
                'periode' => $p->periode,
                'id_karyawan' => $p->id_karyawan,
                'id_kriteria' => $p->id_kriteria,
                'nilai' => $nilai
            ]);
        }

        return response()->json(["response" => "success"]);
    }

    public function update_get_penilaian_karyawan(Request $request)
    {
        $penilaian = Penilaian::where('periode', $request->periode)->where('id_karyawan', $request->id_karyawan)->get();
        $subKriteria = SubKriteria::all();
        $data = [];
        foreach ($penilaian as $p) {
            $data[] = [
                "id_kriteria" => $p->kriteria->id,
                "kriteria" => $p->kriteria->nama,
                "nilai" => $p->nilai
            ];
        }

        $response = [
            "response" => "success",
            "data" => $data,
            "subKriteria" => $subKriteria
        ];

        return response()->json($response);
    }

    public function update_penilaian_karyawan(Request $request)
    {
        foreach ($request->kriteria as $key => $value) {
            Penilaian::where('periode', $request->periode)->where('id_karyawan', $request->id_karyawan)->where('id_kriteria', $value["id_kriteria"])->update([
                "nilai" => $value["nilai"]
            ]);
        }

        $npenilaian = NPenilaian::where('periode', $request->periode)->get();
        foreach ($npenilaian as $np) {
            NPenilaian::where('id', $np->id)->delete();
        }

        $penilaian = Penilaian::where('periode', $request->periode)->get();

        foreach ($penilaian as $p) {
            $maxVal = Penilaian::where('periode', $request->periode)->where('id_kriteria', $p->id_kriteria)->max('nilai');
            $nilai = 0;
            if ($maxVal > 0) {
                $nilai = $p->nilai / $maxVal;
                $nilai = number_format((float)$nilai, 2, '.', '');
            }
            $np_id = NPenilaian::max('id');
            $np_id = $np_id + 1;
            NPenilaian::create([
                'id' => $np_id,
                'periode' => $p->periode,
                'id_karyawan' => $p->id_karyawan,
                'id_kriteria' => $p->id_kriteria,
                'nilai' => $nilai
            ]);
        }

        return response()->json(["response" => "success"]);
    }

    public function get_normalisasi_penilaian_karyawan(Request $request)
    {
        $npenilaian = NPenilaian::where('periode', $request->periode)->get();
        if (count($npenilaian) == 0) {
            return response()->json(["response" => false, "periode" => $request->periode]);
        } else {
            $karyawan = Karyawan::all();
            $kriteria = Kriteria::all();
            $data = [];

            foreach ($karyawan as $karyawanKey => $karyawanVal) {
                $data[$karyawanKey]["id_karyawan"] = $karyawanVal->id;
                $data[$karyawanKey]["nama"] = $karyawanVal->nama;

                foreach ($kriteria as $kriteriaKey => $kriteriaVal) {
                    foreach ($npenilaian as $npKey => $pVal) {
                        if ($npenilaian[$npKey]["id_karyawan"] == $karyawanVal->id && $npenilaian[$npKey]["id_kriteria"] == $kriteriaVal->id) {
                            $data[$karyawanKey]["nilai"][] = [
                                "kriteria" => $npenilaian[$npKey]["id_kriteria"],
                                "nilai" => $npenilaian[$npKey]["nilai"]
                            ];
                        }
                    }
                }
            }

            $response = [
                "response" => true,
                "periode" => $request->periode,
                "data" => $data
            ];
            return response()->json($response);
        }
    }

    public function get_final_result(Request $request)
    {
        $result = [];

        $karyawan = Karyawan::all();
        foreach ($karyawan as $kKey => $kVal) {
            $result[] = [
                "id_karyawan" => $kVal->id,
                "nama" => $kVal->nama
            ];

            $npenilaian = NPenilaian::where('periode', $request->periode)->where('id_karyawan', $kVal->id)->get();
            $nkriteria = NKriteria::all();
            $nilai = 0;
            foreach ($npenilaian as $npKey => $npVal) {
                $bobot = 0;

                foreach ($nkriteria as $nkKey => $nkVal) {
                    if ($nkVal->id == $npVal->id_kriteria) {
                        $bobot = $nkVal->bobot;
                    }
                }

                $nilai = $nilai + ($npVal->nilai * $bobot);
            }

            $result[$kKey]["nilai"] = number_format((float)$nilai, 2, '.', '');
        }

        usort($result, function ($a, $b) {
            return $b['nilai'] <=> $a['nilai'];
        });

        return response()->json($result);
    }
}
