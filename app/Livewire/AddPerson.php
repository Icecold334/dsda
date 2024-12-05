<?php

namespace App\Livewire;

use App\Models\Person;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AddPerson extends Component
{
    public $id;
    public $tipe;
    public $persons;
    public $keterangan;
    public $alamat;
    public $telepon;
    public $email;
    public $jabatan;
    public $person;

    public function mount()
    {
        $this->persons = Person::all();

        if ($this->id) {
            $person = Person::find($this->id);
            $this->person = $person->nama;
            $this->alamat = $person->alamat;
            $this->telepon = $person->telepon;
            $this->email = $person->email;
            $this->jabatan = $person->jabatan;
            $this->keterangan = $person->keterangan;
        }
    }

    public function removePerson()
    {
        Person::destroy($this->id);
        return redirect()->route('person.index');
    }
    public function savePerson()
    {
        $data = [
            'nama' => $this->person,
            'alamat' => $this->alamat,
            'telepon' => $this->telepon,
            'email' => $this->email,
            'jabatan' => $this->jabatan,
            'keterangan' => $this->keterangan,
            'nama_nospace' => Str::slug($this->person),
        ];
        // Jika ID diberikan, cari kategori
        $person = Person::find($this->id);

        // Set user_id
        $data['user_id'] = $person ? $person->user_id : Auth::id();

        // Update atau create dengan data
        Person::updateOrCreate(['id' => $this->id ?? 0], $data);

        return redirect()->route('person.index');
    }
    public function render()
    {
        return view('livewire.add-person');
    }
}
