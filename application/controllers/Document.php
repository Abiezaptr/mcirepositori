<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Document extends CI_Controller
{

    public function index()
    {
        $data['title'] = 'Uploads Document';

        $query = $this->db->get('type'); // Mengambil data dari tabel types
        $type = $query->result();
        $data['type'] = $type; // Menambahkan data type ke array data yang akan dikirim ke view

        $query = $this->db->get('product'); // Mengambil data dari tabel product
        $product = $query->result();
        $data['product'] = $product; // Menambahkan data product ke array data yang akan dikirim ke view

        $data['logs'] = $this->get_upload_logs();
        $data['read_logs'] = $this->get_user_read_logs();

        $this->load->view('template/header', $data);
        $this->load->view('document/index', $data); // Mengirim data kategori ke view
        $this->load->view('template/footer');
    }

    // Method untuk menandai notifikasi sebagai sudah dibaca
    public function readnotif()
    {
        $log_id = $this->input->post('log_id');
        $user_id = $this->session->userdata('id'); // Ambil user ID dari session

        // Update status is_read hanya untuk notifikasi milik user yang sedang login
        $data = array(
            'log_id' => $log_id,
            'user_id' => $user_id
        );

        $this->db->insert('user_read_logs', $data);

        echo json_encode(['status' => 'success']);
    }

    // Method untuk mengambil semua log
    public function get_upload_logs()
    {
        $this->db->select('upload_log.*, users.username, document.name as document_name');
        $this->db->from('upload_log');
        $this->db->join('users', 'upload_log.user_id = users.id');
        $this->db->join('document', 'upload_log.document_id = document.id');
        $this->db->order_by('upload_time', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result();
    }

    // Method untuk mengambil log yang sudah dibaca oleh pengguna yang sedang login
    public function get_user_read_logs()
    {
        $user_id = $this->session->userdata('id');
        $this->db->select('log_id');
        $this->db->from('user_read_logs');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        return array_column($query->result_array(), 'log_id');
    }

    public function uploads()
    {
        // Atur timezone ke Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $type_id            = $this->input->post('type_id', true);
        $product_id         = $this->input->post('product_id', true);
        $user_id            = $this->input->post('user_id', true);
        $name               = $this->input->post('name', true);
        $description        = $this->input->post('description', true);
        $summary            = $this->input->post('summary', true);
        $file               = $_FILES['file']['name'];
        $thumbnail          = $_FILES['thumbnail']['name'];

        // Upload file
        if ($file != '') {
            $config['upload_path']   = './uploads';
            $config['allowed_types'] = 'pdf';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file')) {
                $this->session->set_flashdata('error', 'File upload failed');
                redirect('document');
            } else {
                $file = $this->upload->data('file_name');
                $this->session->set_flashdata('success', 'File uploaded successfully');
            }
        }

        // Upload thumbnail
        if ($thumbnail != '') {
            $config['upload_path']   = './uploads/thumbnail';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('thumbnail')) {
                $this->session->set_flashdata('error', 'Thumbnail upload failed');
                redirect('document');
            } else {
                $thumbnail = $this->upload->data('file_name');
                $this->session->set_flashdata('success', 'Thumbnail uploaded successfully');
            }
        }

        $data = [
            'type_id'       => $type_id,
            'product_id'    => $product_id,
            'user_id'       => $user_id,
            'name'          => $name,
            'description'   => $description,
            'summary'       => $summary,
            'file'          => $file,
            'thumbnail'     => $thumbnail,
            'upload_date'   => date('Y-m-d H:i:s'),
        ];

        $this->db->insert('document', $data);
        $document_id = $this->db->insert_id();  // Ambil ID dokumen yang baru saja diunggah

        // Ambil username dari session ID
        $session_user_id = $this->session->userdata('id');  // Menggunakan 'id' dari session
        $this->db->select('username');
        $this->db->from('users');
        $this->db->where('id', $session_user_id);
        $query = $this->db->get();
        $user = $query->row();

        // Simpan log ke dalam tabel upload_log
        $log_data = [
            'user_id'       => $user_id,
            'document_id'   => $document_id,
            'upload_time'   => date('Y-m-d H:i:s'),  // Menggunakan waktu Asia/Jakarta
            'message'       => $user->username . ' telah menambah dokumen baru, ' . $name,
            'is_read'       => false
        ];
        $this->db->insert('upload_log', $log_data);

        $this->session->set_flashdata('success', 'Document and thumbnail uploaded successfully');
        redirect('home');
    }


    // public function view($id)
    // {
    //     $data['title'] = "Detail Document";

    //     $this->db->select('document.*, type.name as type_name');
    //     $this->db->from('document');
    //     $this->db->join('type', 'type.id = document.type_id');
    //     $this->db->where('document.id', $id);
    //     $document = $this->db->get()->row();

    //     if ($document) {
    //         $filename = pathinfo($document->file, PATHINFO_FILENAME);
    //         $filename = preg_replace('/[_\-]+/', ' ', $filename); // Mengganti semua underscore dan dash dengan spasi
    //         $filename = preg_replace('/\d{2,4}/', '', $filename); // Menghapus angka yang muncul berurutan
    //         $filename = ucwords($filename); // Kapitalisasi setiap kata
    //         $document->file_name = $filename;

    //         // Update or insert into user_views
    //         $user_id = $this->session->userdata('id'); // Asumsi user_id tersimpan di session
    //         $this->updateUserViews($user_id, $id);
    //     }

    //     $data['document'] = $document;
    //     $data['logs'] = $this->get_upload_logs();
    //     $data['read_logs'] = $this->get_user_read_logs();

    //     $this->load->view('template/header', $data);
    //     $this->load->view('document/detail', $data);
    //     $this->load->view('template/footer');
    // }

    public function view($id)
    {
        $data['title'] = "Detail Document";

        $this->db->select('document.*, type.name as type_name');
        $this->db->from('document');
        $this->db->join('type', 'type.id = document.type_id');
        $this->db->where('document.id', $id);
        $document = $this->db->get()->row();

        if ($document) {
            $filename = pathinfo($document->file, PATHINFO_FILENAME);
            $filename = preg_replace('/[_\-]+/', ' ', $filename); // Replace underscores and dashes with spaces
            $filename = preg_replace('/\d{2,4}/', '', $filename); // Remove consecutive digits
            $filename = ucwords($filename); // Capitalize each word
            $document->file_name = $filename;

            // Update or insert into user_views
            $user_id = $this->session->userdata('id'); // Assume user_id is stored in session
            $this->updateUserViews($user_id, $id);

            // Log document view
            $this->log_document_view($user_id, $id);
        }

        $data['document'] = $document;
        $data['logs'] = $this->get_upload_logs();
        $data['read_logs'] = $this->get_user_read_logs();

        $this->load->view('template/header', $data);
        $this->load->view('document/detail', $data);
        $this->load->view('template/footer');
    }

    private function log_document_view($user_id, $document_id)
    {
        // Get IP address and browser info
        $ip_address = $this->input->ip_address();
        $browser = $this->input->user_agent();

        // Insert data into database
        $this->db->insert('document_views', [
            'user_id' => $user_id,
            'document_id' => $document_id,
            'ip_address' => $ip_address
        ]);
    }

    private function updateUserViews($user_id, $document_id)
    {
        $last_viewed = date('Y-m-d H:i:s'); // Get current datetime

        $this->db->where('user_id', $user_id);
        $this->db->where('document_id', $document_id);
        $query = $this->db->get('user_views');

        if ($query->num_rows() > 0) {
            // Update existing record
            $this->db->where('user_id', $user_id);
            $this->db->where('document_id', $document_id);
            $this->db->update('user_views', ['last_viewed' => $last_viewed]);
        } else {
            // Insert new record
            $this->db->insert('user_views', [
                'user_id' => $user_id,
                'document_id' => $document_id,
                'last_viewed' => $last_viewed
            ]);
        }
    }


    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function update($id)
    {
        $data['title'] = "Update Document";
        $this->db->select('document.*, category.category_name');
        $this->db->from('document');
        $this->db->join('category', 'category.category_id = document.category_id');
        $this->db->where('document.document_id', $id);
        $document = $this->db->get()->row();

        if ($document) {
            $filename = pathinfo($document->file, PATHINFO_FILENAME);
            $filename = preg_replace('/[_\-]+/', ' ', $filename); // Mengganti semua underscore dan dash dengan spasi
            $filename = preg_replace('/\d{2,4}/', '', $filename); // Menghapus angka yang muncul berurutan
            $filename = ucwords($filename); // Kapitalisasi setiap kata
            $document->file_name = $filename;

            // Menambahkan tipe dokumen
            $document->file_type = pathinfo($document->file, PATHINFO_EXTENSION);

            $filePath = './uploads/' . $document->file;
            if (file_exists($filePath)) {
                $document->file_size = $this->formatSizeUnits(filesize($filePath));
            } else {
                $document->file_size = '-';
            }
        }

        $data['document'] = $document;
        $data['logs'] = $this->get_upload_logs();
        $data['read_logs'] = $this->get_user_read_logs();

        $this->load->view('template/header', $data);
        $this->load->view('document/update', $data);
        $this->load->view('template/footer');
    }

    public function update_process()
    {
        $document_id = $this->input->post('document_id', true);
        $description = $this->input->post('description', true);

        // Membuat array data hanya dengan deskripsi yang diperbarui
        $data = [
            'description' => $description,
        ];

        // Mengatur zona waktu ke Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        $data['last_viewed'] = date('Y-m-d H:i:s'); // Memperbarui waktu terakhir dilihat jika diperlukan

        // Memperbarui data di database
        $this->db->where('document_id', $document_id);
        $this->db->update('document', $data);

        // Mengatur pesan sukses dan mengarahkan kembali ke halaman home
        $this->session->set_flashdata('success', 'Document description updated successfully');
        redirect('home');
    }

    public function remove($document_id)
    {
        // mengambil data file berdasarkan document id
        $this->db->select('file');
        $this->db->from('document');
        $this->db->where('document_id', $document_id);
        $query = $this->db->get();
        $document = $query->row();

        if ($document) {
            $filePath = './uploads/' . $document->file;
            if (file_exists($filePath)) {
                unlink($filePath); // Menghapus file dari server
            }

            // Menghapus data dari database
            $this->db->where('document_id', $document_id);
            $this->db->delete('document');

            $this->session->set_flashdata('success', 'Document deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Document not found');
        }

        redirect('home');
    }

    public function get_documents()
    {
        // Ambil semua jenis type
        $query = $this->db->get('type');
        $types = $query->result();

        // Ambil user_id dari session
        $user_id = $this->session->userdata('id');

        // Query untuk mengambil dokumen terbaru yang dilihat oleh user berdasarkan type
        $this->db->select('document.*, type.id as type_id, type.name as type_name, product.id as product_id, product.name as product_name, users.username as upload_name, user_views.last_viewed');
        $this->db->from('document');
        $this->db->join('type', 'type.id = document.type_id', 'left');
        $this->db->join('product', 'product.id = document.product_id', 'left');
        $this->db->join('users', 'users.id = document.user_id', 'left');
        $this->db->join('user_views', 'user_views.document_id = document.id AND user_views.user_id = ' . $user_id, 'left');
        $this->db->order_by('user_views.last_viewed', 'DESC'); // Urutkan berdasarkan last_viewed secara descending
        $documents = $this->db->get()->result();

        // Proses pengolahan filename untuk setiap dokumen
        foreach ($documents as &$doc) {
            $filename = pathinfo($doc->file, PATHINFO_FILENAME);
            $filename = preg_replace('/[_\-]+/', ' ', $filename); // Mengganti semua underscore dan dash dengan spasi
            $filename = preg_replace('/\d{2,4}/', '', $filename); // Menghapus angka yang muncul berurutan
            $filename = ucwords($filename); // Kapitalisasi setiap kata

            // Simpan filename yang telah diolah ke dalam objek dokumen
            $doc->filename_processed = $filename;
        }

        // Siapkan data untuk dikirim sebagai JSON
        $data = array(
            'types' => $types,
            'documents' => $documents
        );

        // Kirim data dalam format JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    }


    public function search_documents()
    {
        $keyword = $this->input->post('keyword', true);
        $type_id = $this->input->post('type_id', true);

        $keyword = $this->db->escape_like_str($keyword);
        $keyword = str_replace(['_', '-'], ' ', $keyword);

        $sql = "SELECT document.id as document_id, document.description, document.thumbnail, document.upload_date, document.name as document_name, file, type.name as work_type
            FROM document
            JOIN type ON type.id = document.type_id
            WHERE 1=1"; // Tambahkan kondisi 1=1 untuk mempermudah penambahan kondisi berikutnya

        if (!empty($keyword)) {
            $sql .= " AND (REPLACE(REPLACE(document.name, '_', ' '), '-', ' ') LIKE '%$keyword%' 
                 OR REPLACE(REPLACE(file, '_', ' '), '-', ' ') LIKE '%$keyword%')";
        }

        if ($type_id != 'all') {
            $type_id = $this->db->escape_str($type_id);
            $sql .= " AND type.id = '$type_id'";
        }

        $query = $this->db->query($sql);

        $documents = [];
        foreach ($query->result() as $row) {
            $filename = pathinfo($row->file, PATHINFO_FILENAME);
            $filename = preg_replace('/[_\-]+/', ' ', $filename);
            $filename = preg_replace('/\d{2,4}/', '', $filename);
            $filename = ucwords($filename);
            $documents[] = [
                'name' => $row->document_name,
                'label' => $filename,
                'value' => $row->document_id,
                'description' => $row->description,
                'thumbnail' => $row->thumbnail,
                'upload_date' => $row->upload_date,
                'work_type' => $row->work_type
            ];
        }

        echo json_encode($documents);
    }
}
