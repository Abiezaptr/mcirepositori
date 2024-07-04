<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Files extends CI_Controller
{

    public function index()
    {
        $data['title'] = 'Files Management';

        $query = $this->db->get('category'); // Mengambil data dari tabel categories
        $categories = $query->result();
        $data['categories'] = $categories;

        $user_id = $this->session->userdata('id'); // Mengambil user_id dari session

        // Query untuk mengambil dokumen terbaru yang dilihat oleh user
        $this->db->select('document.*, category.category_name, user_views.last_viewed');
        $this->db->from('document');
        $this->db->join('category', 'category.category_id = document.category_id', 'left');
        $this->db->join('user_views', 'user_views.document_id = document.document_id AND user_views.user_id = ' . $user_id, 'left');
        $this->db->order_by('document.created_at', 'DESC'); // Urutkan berdasarkan last_viewed secara descending
        $query = $this->db->get();
        $documents = $query->result();

        foreach ($documents as $doc) {
            $filename = pathinfo($doc->file, PATHINFO_FILENAME);
            $filename = preg_replace('/[_\-]+/', ' ', $filename); // Mengganti semua underscore dan dash dengan spasi
            $filename = preg_replace('/\d{2,4}/', '', $filename); // Menghapus angka yang muncul berurutan
            $filename = ucwords($filename); // Kapitalisasi setiap kata

            $doc->file_name = $filename;

            $filePath = './uploads/' . $doc->file;
            if (file_exists($filePath)) {
                $doc->file_size = $this->formatSizeUnits(filesize($filePath));
            } else {
                $doc->file_size = '-';
            }
        }

        $data['documents'] = $documents;

        $this->load->view('template/header', $data);
        $this->load->view('files/index', $data); // Mengirim data kategori ke view
        $this->load->view('template/footer');
    }

    public function uploads()
    {
        $document_id      = $this->input->post('document_id', true);
        $category_id      = $this->input->post('category_id', true);
        $description      = $this->input->post('description', true);
        $file             = $_FILES['file']['name'];

        if ($file != '') {
            $config['upload_path']   = './uploads';
            $config['allowed_types'] = 'pdf';
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file')) {
                $this->session->set_flashdata('error', 'Upload failed');
                redirect('document');
            } else {
                $file = $this->upload->data('file_name');
                $this->session->set_flashdata('success', 'Document uploaded successfully');
            }
        }

        $data = [
            'document_id'  => $document_id,
            'category_id'  => $category_id,
            'description'  => $description,
            'file'         => $file,
            'created_at'   => date('Y-m-d H:i:s'),
            'last_viewed'  => date('Y-m-d H:i:s'),
        ];

        $this->db->insert('document', $data);

        $this->session->set_flashdata('success', 'Document uploaded successfully');
        redirect('home');
    }

    public function view($id)
    {
        $data['title'] = "Detail Document";
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

            $filePath = './uploads/' . $document->file;
            if (file_exists($filePath)) {
                $document->file_size = $this->formatSizeUnits(filesize($filePath));
            } else {
                $document->file_size = '-';
            }

            // Mengatur zona waktu ke Asia/Jakarta
            date_default_timezone_set('Asia/Jakarta');
            $last_viewed = date('Y-m-d H:i:s');

            // Memperbarui last_viewed di database
            $this->db->where('document_id', $id);
            $this->db->update('document', ['last_viewed' => $last_viewed]);

            // Update or insert into user_views
            $user_id = $this->session->userdata('id'); // Asumsi user_id tersimpan di session
            $this->updateUserViews($user_id, $id, $last_viewed);
        }

        $data['document'] = $document;

        $this->load->view('template/header', $data);
        $this->load->view('document/detail', $data);
        $this->load->view('template/footer');
    }

    private function updateUserViews($user_id, $document_id, $last_viewed)
    {
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

    public function search_documents()
    {
        $keyword = $this->input->post('keyword', true);
        $category_id = $this->input->post('category_id', true);

        $keyword = $this->db->escape_like_str($keyword);
        $keyword = str_replace(['_', '-'], ' ', $keyword);

        $sql = "SELECT document_id, file, category.category_name
            FROM document
            JOIN category ON category.category_id = document.category_id
            WHERE 1=1"; // Tambahkan kondisi 1=1 untuk mempermudah penambahan kondisi berikutnya

        if (!empty($keyword)) {
            $sql .= " AND REPLACE(REPLACE(file, '_', ' '), '-', ' ') LIKE '%$keyword%'";
        }

        if ($category_id != 'all') {
            $category_id = $this->db->escape_str($category_id);
            $sql .= " AND category.category_id = '$category_id'";
        }

        $query = $this->db->query($sql);

        $documents = [];
        foreach ($query->result() as $row) {
            $filename = pathinfo($row->file, PATHINFO_FILENAME);
            $filename = preg_replace('/[_\-]+/', ' ', $filename);
            $filename = preg_replace('/\d{2,4}/', '', $filename);
            $filename = ucwords($filename);
            $documents[] = [
                'label' => $filename,
                'value' => $row->document_id,
                'category_name' => $row->category_name
            ];
        }

        echo json_encode($documents);
    }
}
