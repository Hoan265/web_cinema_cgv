<?php
// Include film model
require_once __DIR__ . '/../Models/FilmModel.php';
class QuanLyPhim extends Controller
{
    private $filmModel;

    public function __construct() {
        // Tạo một instance mới của FilmModel
        $this->filmModel = new FilmModel();
        parent::__construct();
    }
    public function default()
    {
            $this->view("main_layout",[
                "Page" => "QuanLyPhim",
                "Title" => "Quản lý phim",
                "PageRole" => "admin",                             
            ]);
    }
    // Phương thức để lấy thông tin của một bộ phim theo id
    public function getFilmById($filmId) {
        return $this->filmModel->getFilmById($filmId);
    }  
    // Phương thức để lấy tất cả các bộ phim
    public function getFilms() {
        return $this->filmModel->getAllFilms();
    }

    // Phương thức để thêm một bộ phim mới
    public function addFilm($filmData) {
        return $this->filmModel->addFilm($filmData);
    }

    // Phương thức để cập nhật thông tin của một bộ phim
    public function updateFilm($filmId, $filmData) {
        return $this->filmModel->updateFilm($filmId, $filmData);
    }

    // Phương thức để xóa một bộ phim
    public function deleteFilm($filmId) {
        return $this->filmModel->deleteFilm($filmId);
    }
    public function getDetailFilmById($filmId) {
        return $this->filmModel->getById($filmId);
    }
}
?>
