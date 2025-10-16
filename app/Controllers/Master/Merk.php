<?php
/**
 * Merk Controller
 * 
 * Controller for managing brands (merk)
 * Handles CRUD operations and other related functionalities
 * 
 * @author    Mikhael Felian Waskito <mikhaelfelian@gmail.com>
 * @date      2025-01-12
 */

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\MerkModel;

class Merk extends BaseController
{
    protected $merkModel;
    protected $validation;

    public function __construct()
    {
        $this->merkModel = new MerkModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $curr_page  = $this->request->getVar('page_merk') ?? 1;
        $per_page   = 10;
        $query      = $this->request->getVar('keyword') ?? '';

        // Apply search filter if keyword exists
        if ($query) {
            $this->merkModel->groupStart()
                ->like('merk', $query)
                ->orLike('kode', $query)
                ->orLike('keterangan', $query)
                ->groupEnd();
        }

        $data = [
            'title'         => 'Data Merk',
            'Pengaturan'    => $this->pengaturan,
            'user'          => $this->ionAuth->user()->row(),
            'merk'          => $this->merkModel->paginate($per_page, 'merk'),
            'pager'         => $this->merkModel->pager,
            'currentPage'   => $curr_page,
            'perPage'       => $per_page,
            'keyword'       => $query,
            'breadcrumbs'   => '
                <li class="breadcrumb-item"><a href="' . base_url() . '">Beranda</a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Merk</li>
            '
        ];

        return view($this->theme->getThemePath() . '/master/merk/index', $data);
    }

    public function create()
    {
        $data = [
            'title'         => 'Form Merk',
            'Pengaturan'    => $this->pengaturan,
            'user'          => $this->ionAuth->user()->row(),
            'validation'    => $this->validation,
            'breadcrumbs'   => '
                <li class="breadcrumb-item"><a href="' . base_url() . '">Beranda</a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item"><a href="' . base_url('master/merk') . '">Merk</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            '
        ];

        return view($this->theme->getThemePath() . '/master/merk/create', $data);
    }

    public function store()
    {
        $merk   = $this->request->getPost('merk');
        $ket    = $this->request->getPost('keterangan');
        $status = $this->request->getPost('status') ?? '1'; // Default to active if not provided

        // Validation rules
        $rules = [
            'merk' => [
                'rules' => 'required|max_length[160]',
                'errors' => [
                    'required' => 'Merk harus diisi',
                    'max_length' => 'Merk maksimal 160 karakter'
                ]
            ],
            'status' => [
                'rules' => 'in_list[0,1]',
                'errors' => [
                    'in_list' => 'Status harus 0 atau 1'
                ]
            ],
            env('security.tokenName', 'csrf_test_name') => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'CSRF token tidak valid'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Validasi gagal');
        }

        // Generate brand code
        $kode   = $this->merkModel->generateKode($merk);

        $data = [
            'kode'       => $kode,
            'merk'       => $merk,
            'keterangan' => $ket,
            'status'     => $status,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        try {
            if ($this->merkModel->insert($data)) {
                return redirect()->to(base_url('master/merk'))
                    ->with('success', 'Data merk berhasil ditambahkan');
            } else {
                // Get the last error from the model
                $errors = $this->merkModel->errors();
                $errorMessage = !empty($errors) ? implode(', ', $errors) : 'Gagal menambahkan data merk';
                
                return redirect()->to(base_url('master/merk'))
                    ->with('error', $errorMessage)
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->to(base_url('master/merk'))
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $data = [
            'title'         => 'Form Merk',
            'Pengaturan'    => $this->pengaturan,
            'user'          => $this->ionAuth->user()->row(),
            'validation'    => $this->validation,
            'breadcrumbs'   => '
                <li class="breadcrumb-item"><a href="' . base_url() . '">Beranda</a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item"><a href="' . base_url('master/merk') . '">Merk</a></li>
                <li class="breadcrumb-item active">Edit</li>
            '
        ];

        $data['merk'] = $this->merkModel->find($id);

        if (empty($data['merk'])) {
            return redirect()->to(base_url('master/merk'))
                ->with('error', 'Data merk tidak ditemukan');
        }

        return view($this->theme->getThemePath() . '/master/merk/edit', $data);
    }

    public function update($id)
    {
        // Validation rules
        $rules = [
            'merk' => [
                'rules' => 'required|max_length[160]',
                'errors' => [
                    'required' => 'Merk harus diisi',
                    'max_length' => 'Merk maksimal 160 karakter'
                ]
            ],
            env('security.tokenName', 'csrf_test_name') => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'CSRF token tidak valid'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Validasi gagal');
        }

        $data = [
            'merk'       => $this->request->getPost('merk'),
            'keterangan' => $this->request->getPost('keterangan'),
            'status'     => $this->request->getPost('status')
        ];

        if ($this->merkModel->update($id, $data)) {
            return redirect()->to(base_url('master/merk'))
                ->with('success', 'Data merk berhasil diubah!');
        }

        return redirect()->back()
            ->with('error', 'Gagal mengupdate data merk')
            ->withInput();
    }

    public function delete($id)
    {
        if ($this->merkModel->delete($id)) {
            return redirect()->to(base_url('master/merk'))
                ->with('success', 'Data merk berhasil dihapus');
        }

        return redirect()->back()
            ->with('error', 'Gagal menghapus data merk');
    }

    /**
     * Show CSV import form
     */
    public function importForm()
    {
        $data = [
            'title'         => 'Import Data Merk',
            'Pengaturan'    => $this->pengaturan,
            'user'          => $this->ionAuth->user()->row(),
            'breadcrumbs'   => '
                <li class="breadcrumb-item"><a href="' . base_url() . '">Beranda</a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item"><a href="' . base_url('master/merk') . '">Merk</a></li>
                <li class="breadcrumb-item active">Import CSV</li>
            '
        ];

        return view($this->theme->getThemePath() . '/master/merk/import', $data);
    }

    /**
     * Process CSV import
     */
    public function importCsv()
    {
        $file = $this->request->getFile('csv_file');
        
        if (!$file || !$file->isValid()) {
            return redirect()->back()
                ->with('error', 'File CSV tidak valid');
        }

        // Validation rules
        $rules = [
            'csv_file' => [
                'rules' => 'uploaded[csv_file]|ext_in[csv_file,csv]|max_size[csv_file,2048]',
                'errors' => [
                    'uploaded' => 'File CSV harus diupload',
                    'ext_in' => 'File harus berformat CSV',
                    'max_size' => 'Ukuran file maksimal 2MB'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()));
        }

        try {
            $csvData = [];
            $handle = fopen($file->getTempName(), 'r');
            
            // Skip header row
            $header = fgetcsv($handle);
            
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 2) { // At least merk and keterangan
                    $csvData[] = [
                        'merk' => trim($row[0]),
                        'keterangan' => isset($row[1]) ? trim($row[1]) : '',
                        'status' => isset($row[2]) ? trim($row[2]) : '1'
                    ];
                }
            }
            fclose($handle);

            if (empty($csvData)) {
                return redirect()->back()
                    ->with('error', 'File CSV kosong atau format tidak sesuai');
            }

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($csvData as $index => $data) {
                try {
                    // Generate kode
                    $kode = $this->merkModel->generateKode($data['merk']);
                    
                    $insertData = [
                        'kode' => $kode,
                        'merk' => $data['merk'],
                        'keterangan' => $data['keterangan'],
                        'status' => $data['status'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    if ($this->merkModel->insert($insertData)) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "Baris " . ($index + 2) . ": " . implode(', ', $this->merkModel->errors());
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $message = "Import selesai. Berhasil: {$successCount}, Gagal: {$errorCount}";
            if (!empty($errors)) {
                $message .= "<br>Error details:<br>" . implode("<br>", array_slice($errors, 0, 10));
                if (count($errors) > 10) {
                    $message .= "<br>... dan " . (count($errors) - 10) . " error lainnya";
                }
            }

            return redirect()->to(base_url('master/merk'))
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $filename = 'template_merk.csv';
        $filepath = FCPATH . 'assets/templates/' . $filename;
        
        // Create template if not exists
        if (!file_exists($filepath)) {
            $templateDir = dirname($filepath);
            if (!is_dir($templateDir)) {
                mkdir($templateDir, 0777, true);
            }
            
            $template = "Merk,Keterangan,Status\n";
            $template .= "Samsung,Produk elektronik Samsung,1\n";
            $template .= "Apple,Produk Apple Inc,1\n";
            $template .= "Nike,Produk olahraga Nike,1\n";
            
            file_put_contents($filepath, $template);
        }
        
        return $this->response->download($filepath, null);
    }
} 