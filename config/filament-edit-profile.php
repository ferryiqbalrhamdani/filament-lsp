<?php

return [
    'show_custom_fields' => true,
    'custom_fields' => [
        'nik' => [
            'type' => 'text', // required
            'label' => 'NIK', // required
            'placeholder' => 'Contoh: 123456', // optional
            'id' => 'custom-field-1', // optional
            'required' => true, // optional
            'rules' => [], // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
            'suffix_icon' => '', // optional
            'prefix_icon' => '', // optional
            'default' => '', // optional
            'column_span' => 'full', // optional
            'autocomplete' => false, // optional
            'column_span' => 'md'
        ],
        'tgl_lahir' => [
            'type' => 'datetime', // required
            'label' => 'Tanggal Lahir', // required
            'placeholder' => 'DD/MM/YYYY', // optional
            'id' => 'custom-field-2', // optional
            'seconds' => false, // optional
            'required' => true, // optional
            'rules' => [], // optional
            'format' => 'Y-m-d', // optional
            'time' => false, // optional
            'native' => true, // optional
            'column_span' => 'md', // optional
        ],
        'pendidikan_terakhir' => [
            'type' => 'select', // required
            'label' => 'Pendidikan Terakhir', // required
            'placeholder' => 'Contoh: SMA', // optional
            'id' => 'custom-field-3', // optional
            'required' => true, // optional
            'options' => [
                'sd' => 'SD',
                'smp' => 'SMP',
                'sma' => 'SMA',
                'd3' => 'D3',
                's1' => 'S1',
                's2' => 'S2',
                's3' => 'S3',
            ], // optional
            'column_span' => 'md', // optional
        ],
        'jenis_kelamin' => [
            'type' => 'select', // required
            'label' => 'Jenis Kelamin', // required
            'placeholder' => 'Contoh: Laki-laki', // optional
            'id' => 'custom-field-4', // optional
            'hint_icon' => '', // optional
            'required' => true, // optional
            'hint' => '', // optional
            'default' => '', // optional
            'rules' => [], // optional
            'options' => [
                'laki-laki' => 'Laki-laki',
                'perempuan' => 'Perempuan',
            ], // optional
            'column_span' => 'md', // optional
        ],
        'tempat_lahir' => [
            'type' => 'text', // required
            'label' => 'Tempat Lahir', // required
            'placeholder' => 'Contoh: Bandung', // optional
            'id' => 'custom-field-5', // optional
            'required' => true, // optional
            'rules' => [], // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
            'suffix_icon' => '', // optional
            'prefix_icon' => '', // optional
            'default' => '', // optional
            'column_span' => 'full', // optional
            'autocomplete' => false, // optional
            'column_span' => 'md'
        ],
        'kewarganegaraan' => [
            'type' => 'text', // required
            'label' => 'Kewarganegaraan', // required
            'placeholder' => 'Contoh: Indonesia', // optional
            'id' => 'custom-field-6', // optional
            'required' => true, // optional
            'rules' => [], // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
            'suffix_icon' => '', // optional
            'prefix_icon' => '', // optional
            'default' => '', // optional
            'column_span' => 'full', // optional
            'autocomplete' => false, // optional
            'column_span' => 'md'
        ],
        'alamat' => [
            'type' => 'textarea', // required
            'label' => 'Alamat', // required
            'placeholder' => 'Contoh: Jl. Raya No. 123', // optional
            'id' => 'custom-field-7', // optional
            'rows' => '3', // optional
            'required' => true, // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
            'default' => '', // optional
            'rules' => [], // optional
            'column_span' => 'full', // optional
        ],
        'no_wa' => [
            'type' => 'text', // required
            'label' => 'No. Whatsapp', // required
            'placeholder' => 'Contoh: 08123456789', // optional
            'id' => 'custom-field-8', // optional
            'required' => true, // optional
            'rules' => [
                'numeric',
            ], // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
            'suffix_icon' => '', // optional
            'prefix_icon' => '', // optional
            'default' => '', // optional
            'column_span' => 'full', // optional
            'autocomplete' => false, // optional
            'column_span' => 'md'
        ],

    ],
];
