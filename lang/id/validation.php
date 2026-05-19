<?php

/*
|--------------------------------------------------------------------------
| Validation Language Lines — Bahasa Indonesia
| FILE: lang/id/validation.php
|
| Cara aktifkan:
| 1. Letakkan file ini di: lang/id/validation.php
| 2. Di config/app.php ubah: 'locale' => 'id',
| 3. Atau di .env tambahkan: APP_LOCALE=id
|--------------------------------------------------------------------------
*/

return [

    'accepted'             => ':attribute harus diterima.',
    'accepted_if'          => ':attribute harus diterima jika :other bernilai :value.',
    'active_url'           => ':attribute bukan URL yang valid.',
    'after'                => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal'       => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa array.',
    'ascii'                => ':attribute hanya boleh berisi karakter alfanumerik satu byte dan simbol.',
    'before'               => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal'      => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'array'   => ':attribute harus memiliki antara :min dan :max item.',
        'file'    => ':attribute harus berukuran antara :min dan :max kilobyte.',
        'numeric' => ':attribute harus bernilai antara :min dan :max.',
        'string'  => ':attribute harus memiliki antara :min dan :max karakter.',
    ],
    'boolean'              => ':attribute harus bernilai true atau false.',
    'can'                  => ':attribute mengandung nilai yang tidak diizinkan.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'contains'             => ':attribute tidak memiliki nilai yang diperlukan.',
    'current_password'     => 'Password saat ini salah.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'date_equals'          => ':attribute harus berupa tanggal yang sama dengan :date.',
    'date_format'          => ':attribute tidak sesuai format :format.',
    'decimal'              => ':attribute harus memiliki :decimal tempat desimal.',
    'declined'             => ':attribute harus ditolak.',
    'declined_if'          => ':attribute harus ditolak jika :other bernilai :value.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus terdiri dari :digits digit.',
    'digits_between'       => ':attribute harus terdiri dari :min sampai :max digit.',
    'dimensions'           => ':attribute memiliki dimensi gambar yang tidak valid.',
    'distinct'             => ':attribute memiliki nilai duplikat.',
    'doesnt_end_with'      => ':attribute tidak boleh diakhiri dengan salah satu nilai berikut: :values.',
    'doesnt_start_with'    => ':attribute tidak boleh diawali dengan salah satu nilai berikut: :values.',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'ends_with'            => ':attribute harus diakhiri dengan salah satu nilai berikut: :values.',
    'enum'                 => ':attribute yang dipilih tidak valid.',
    'exists'               => ':attribute yang dipilih tidak valid.',
    'extensions'           => ':attribute harus memiliki salah satu ekstensi berikut: :values.',
    'file'                 => ':attribute harus berupa file.',
    'filled'               => ':attribute tidak boleh kosong.',
    'gt'                   => [
        'array'   => ':attribute harus memiliki lebih dari :value item.',
        'file'    => ':attribute harus lebih besar dari :value kilobyte.',
        'numeric' => ':attribute harus lebih besar dari :value.',
        'string'  => ':attribute harus lebih dari :value karakter.',
    ],
    'gte'                  => [
        'array'   => ':attribute harus memiliki lebih dari atau sama dengan :value item.',
        'file'    => ':attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
        'string'  => ':attribute harus lebih dari atau sama dengan :value karakter.',
    ],
    'hex_color'            => ':attribute harus berupa warna heksadesimal yang valid.',
    'image'                => ':attribute harus berupa gambar.',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'in_array'             => ':attribute tidak ada dalam :other.',
    'integer'              => ':attribute harus berupa bilangan bulat.',
    'ip'                   => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => ':attribute harus berupa JSON yang valid.',
    'list'                 => ':attribute harus berupa list.',
    'lowercase'            => ':attribute harus berupa huruf kecil.',
    'lt'                   => [
        'array'   => ':attribute harus memiliki kurang dari :value item.',
        'file'    => ':attribute harus lebih kecil dari :value kilobyte.',
        'numeric' => ':attribute harus lebih kecil dari :value.',
        'string'  => ':attribute harus kurang dari :value karakter.',
    ],
    'lte'                  => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :value item.',
        'file'    => ':attribute harus lebih kecil dari atau sama dengan :value kilobyte.',
        'numeric' => ':attribute harus lebih kecil dari atau sama dengan :value.',
        'string'  => ':attribute harus kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address'          => ':attribute harus berupa MAC address yang valid.',
    'max'                  => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
        'file'    => ':attribute tidak boleh lebih besar dari :max kilobyte.',
        'numeric' => ':attribute tidak boleh lebih besar dari :max.',
        'string'  => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'max_digits'           => ':attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes'                => ':attribute harus berupa file bertipe: :values.',
    'mimetypes'            => ':attribute harus berupa file bertipe: :values.',
    'min'                  => [
        'array'   => ':attribute harus memiliki setidaknya :min item.',
        'file'    => ':attribute harus berukuran setidaknya :min kilobyte.',
        'numeric' => ':attribute harus minimal :min.',
        'string'  => ':attribute harus memiliki setidaknya :min karakter.',
    ],
    'min_digits'           => ':attribute harus memiliki setidaknya :min digit.',
    'missing'              => ':attribute harus tidak ada.',
    'missing_if'           => ':attribute harus tidak ada jika :other bernilai :value.',
    'missing_unless'       => ':attribute harus tidak ada kecuali :other bernilai :value.',
    'missing_with'         => ':attribute harus tidak ada jika :values ada.',
    'missing_with_all'     => ':attribute harus tidak ada jika :values semua ada.',
    'multiple_of'          => ':attribute harus merupakan kelipatan dari :value.',
    'not_in'               => ':attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'password'             => [
        'letters'       => ':attribute harus mengandung setidaknya satu huruf.',
        'mixed'         => ':attribute harus mengandung setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers'       => ':attribute harus mengandung setidaknya satu angka.',
        'symbols'       => ':attribute harus mengandung setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan pernah muncul dalam kebocoran data. Gunakan :attribute yang berbeda.',
    ],
    'present'              => ':attribute harus ada.',
    'present_if'           => ':attribute harus ada jika :other bernilai :value.',
    'present_unless'       => ':attribute harus ada kecuali :other bernilai :value.',
    'present_with'         => ':attribute harus ada jika :values ada.',
    'present_with_all'     => ':attribute harus ada jika :values semua ada.',
    'prohibited'           => ':attribute dilarang.',
    'prohibited_if'        => ':attribute dilarang jika :other bernilai :value.',
    'prohibited_unless'    => ':attribute dilarang kecuali :other ada dalam :values.',
    'prohibits'            => ':attribute melarang :other untuk hadir.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':attribute wajib diisi.',
    'required_array_keys'  => ':attribute harus mengandung entri untuk: :values.',
    'required_if'          => ':attribute wajib diisi jika :other bernilai :value.',
    'required_if_accepted' => ':attribute wajib diisi jika :other diterima.',
    'required_if_declined' => ':attribute wajib diisi jika :other ditolak.',
    'required_unless'      => ':attribute wajib diisi kecuali :other ada dalam :values.',
    'required_with'        => ':attribute wajib diisi jika :values ada.',
    'required_with_all'    => ':attribute wajib diisi jika :values semua ada.',
    'required_without'     => ':attribute wajib diisi jika :values tidak ada.',
    'required_without_all' => ':attribute wajib diisi jika tidak ada satu pun dari :values.',
    'same'                 => ':attribute harus sama dengan :other.',
    'size'                 => [
        'array'   => ':attribute harus memiliki :size item.',
        'file'    => ':attribute harus berukuran :size kilobyte.',
        'numeric' => ':attribute harus sebesar :size.',
        'string'  => ':attribute harus terdiri dari :size karakter.',
    ],
    'starts_with'          => ':attribute harus diawali dengan salah satu nilai berikut: :values.',
    'string'               => ':attribute harus berupa string.',
    'timezone'             => ':attribute harus berupa zona waktu yang valid.',
    'unique'               => ':attribute sudah digunakan.',
    'uploaded'             => ':attribute gagal diunggah.',
    'uppercase'            => ':attribute harus berupa huruf besar.',
    'url'                  => ':attribute harus berupa URL yang valid.',
    'ulid'                 => ':attribute harus berupa ULID yang valid.',
    'uuid'                 => ':attribute harus berupa UUID yang valid.',

    /*
    |----------------------------------------------------------------------
    | Custom Attribute Names — Label bahasa Indonesia
    | Ini yang muncul sebagai pengganti nama field teknis di pesan error
    |----------------------------------------------------------------------
    */
    'attributes' => [
        // Auth
        'name'                  => 'nama lengkap',
        'email'                 => 'email',
        'password'              => 'kata sandi',
        'password_confirmation' => 'konfirmasi kata sandi',
        'current_password'      => 'kata sandi saat ini',
        'phone'                 => 'nomor HP',
        'identifier'            => 'email atau nomor HP',

        // Order
        'address'               => 'alamat lengkap',
        'address_note'          => 'patokan alamat',
        'zone'                  => 'zona pengiriman',
        'pickup_date'           => 'tanggal jemput',
        'pickup_time'           => 'waktu jemput',
        'weight_estimate'       => 'estimasi berat',
        'service_id'            => 'layanan',
        'notes'                 => 'catatan',
        'customer_address_id'   => 'alamat tersimpan',
        'weight_actual'         => 'berat aktual',
        'proof_image'           => 'foto bukti pengiriman',
        'status'                => 'status pesanan',
        'driver_id'             => 'kurir',
        'assignment_type'       => 'jenis penugasan',

        // Customer Address
        'label'                 => 'label alamat',
        'recipient_name'        => 'nama penerima',
        'full_address'          => 'alamat lengkap',
        'distance_km'           => 'jarak',
        'is_primary'            => 'alamat utama',

        // Finance
        'amount'                => 'jumlah',
        'description'           => 'keterangan',
        'entry_type'            => 'jenis entri',

        // Item lines
        'item_lines'            => 'item satuan',
        'item_lines.*.service_id' => 'layanan item',
        'item_lines.*.qty'        => 'jumlah item',
    ],

    /*
    |----------------------------------------------------------------------
    | Custom Validation Messages — Pesan khusus per field
    | Format: 'field.rule' => 'pesan'
    |----------------------------------------------------------------------
    */
    'custom' => [
        'address' => [
            'required' => 'Alamat lengkap wajib diisi.',
            'min'      => 'Alamat harus diisi lebih lengkap (minimal 10 karakter).',
            'string'   => 'Alamat tidak valid.',
        ],
        'weight_estimate' => [
            'required' => 'Estimasi berat wajib diisi.',
            'numeric'  => 'Estimasi berat harus berupa angka.',
            'min'      => 'Estimasi berat minimal 1 kg.',
            'max'      => 'Estimasi berat maksimal 100 kg.',
        ],
        'pickup_date' => [
            'required'         => 'Tanggal jemput wajib dipilih.',
            'date'             => 'Tanggal jemput tidak valid.',
            'after_or_equal'   => 'Tanggal jemput tidak boleh sebelum hari ini.',
        ],
        'pickup_time' => [
            'required' => 'Waktu jemput wajib dipilih.',
            'in'       => 'Pilihan waktu jemput tidak valid.',
        ],
        'service_id' => [
            'required' => 'Layanan laundry wajib dipilih.',
            'exists'   => 'Layanan yang dipilih tidak tersedia.',
        ],
        'zone' => [
            'required' => 'Zona pengiriman wajib dipilih.',
            'in'       => 'Zona pengiriman tidak valid.',
        ],
        'email' => [
            'required' => 'Email wajib diisi.',
            'email'    => 'Format email tidak valid.',
            'unique'   => 'Email ini sudah terdaftar.',
        ],
        'password' => [
            'required'  => 'Kata sandi wajib diisi.',
            'min'       => 'Kata sandi minimal 8 karakter.',
            'confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ],
        'current_password' => [
            'required'         => 'Kata sandi saat ini wajib diisi.',
            'current_password' => 'Kata sandi saat ini salah.',
        ],
        'proof_image' => [
            'required' => 'Foto bukti pengiriman wajib diupload.',
            'image'    => 'File harus berupa gambar.',
            'max'      => 'Ukuran foto maksimal 5 MB.',
        ],
        'full_address' => [
            'required' => 'Alamat lengkap wajib diisi.',
            'min'      => 'Alamat harus diisi lebih lengkap (minimal 10 karakter).',
        ],
        'label' => [
            'required' => 'Label alamat wajib diisi.',
        ],
        'recipient_name' => [
            'required' => 'Nama penerima wajib diisi.',
        ],
        'amount' => [
            'required' => 'Jumlah wajib diisi.',
            'integer'  => 'Jumlah harus berupa angka.',
            'min'      => 'Jumlah minimal Rp 1.',
        ],
        'description' => [
            'required' => 'Keterangan wajib diisi.',
        ],
    ],
];