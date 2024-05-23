<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Tất cả khuyến mãi</h3>
            <div class="block-options">
                <button type="button" class="btn btn-hero btn-primary show" data-bs-toggle="modal" data-bs-target="#modal-add-promotion" id="btn-and" data-action="create">Thêm khuyến mãi</button>
            </div>
        </div>
        <div class="block-content bg-body-dark">
            <form action="#" id="search-form" onsubmit="return false;">
                <div class="row mb-4">
                    <div class="input-group">
                        <div class="col-md-6 d-flex gap-3">
                            <!-- <button class="btn btn-alt-secondary dropdown-toggle btn-filtered-by-role" id="dropdown-filter-role" type="button" data-bs-toggle="dropdown" aria-expanded="false">ID</button>
                            <ul class="dropdown-menu mt-1" aria-labelledby="dropdown-filter-role">
                                <li><a class="dropdown-item filtered-by-role" href="javascript:void(0)" data-id="0">ID</a></li>
                                <li><a class="dropdown-item filtered-by-role" href="javascript:void(0)" data-id="1">Tên khuyến mãi</a></li>
                                <li><a class="dropdown-item filtered-by-role" href="javascript:void(0)" data-id="2">Thời gian bắt đầu</a></li>
                                <li><a class="dropdown-item filtered-by-role" href="javascript:void(0)" data-id="3">Thời gian kết thúc</a></li>
                                <li><a class="dropdown-item filtered-by-role" href="javascript:void(0)" data-id="4">Giảm giá</a></li>
                                <li><a class="dropdown-item filtered-by-role" href="javascript:void(0)" data-id="5">Trạng thái</a></li>
                                
                            </ul> -->
                            <input type="text" class="form-control form-control-alt" id="search-input" name="search-input" placeholder="Tìm kiếm voucher khuyến mãi...">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="block-content">
            <div class="table-responsive" id="get_promotion">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">ID</th>
                            <th>Tên khuyến mãi</th>
                            <!-- <th class="text-center">Áp dụng</th> -->
                            <th class="text-center">Hình ảnh</th>
                            <th class="text-center">Thời gian bắt đầu</th>
                            <th class="text-center">Thời gian kết thúc</th>
                            <th class="text-center">Giám giá</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="list-promotion">
                    </tbody>
                </table>
                </div>
            <?php if (isset($data["Plugin"]["pagination"]))
                require "./mvc/Views/inc/admin/pagination.php" ?>
            </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-add-promotion" tabindex="-1" role="dialog" aria-labelledby="modal-add-promotion" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-transparent bg-white mb-0 block-rounded">
                <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="btabs-static-home-tab" data-bs-toggle="tab" data-bs-target="#btabs-static-home" role="tab" aria-controls="btabs-static-home" aria-selected="true">
                            Thêm thủ công
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="btabs-static-profile-tab" data-bs-toggle="tab" data-bs-target="#btabs-static-profile" role="tab" aria-controls="btabs-static-profile" aria-selected="false">
                            Thêm từ file
                        </button>
                    </li>
                    <li class="nav-item ms-auto">
                        <button type="button" class="btn btn-close p-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    </li>
                </ul>
                <div class="block-content tab-content">
                    <form novalidate="novalidate" onsubmit="return false;" class="tab-pane active form-add-promotion" id="btabs-static-home" role="tabpanel" aria-labelledby="btabs-static-home-tab" tabindex="0">
                        <div class="mb-4">
                            <label for="makhuyenmai" class="form-label">Mã khuyến mãi</label>
                            <input type="text" class="form-control form-control-alt" name="makhuyenmai" id="makhuyenmai" placeholder="Nhập mã khuyến mãi" disabled>
                        </div>
                        
                        <div class="mb-4">
                            <label for="tenkhuyenmai" class="form-label">Tên khuyến mãi</label>
                            <input type="text" class="form-control form-control-alt" name="tenkhuyenmai" id="tenkhuyenmai" placeholder="Nhập nội dung khuyến mãi">
                        </div>
                        <!-- <div class="mb-4 d-flex gap-4">
                            <label for="voucher" class="form-label">Áp dụng</label>
                            <div class="space-x-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="vephim" name="voucher" value="1">
                                    <label class="form-check-label" for="vephim">Vé xem phim</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="doan" name="voucher" value="0">
                                    <label class="form-check-label" for="doan">Đồ ăn</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="cahai" name="voucher" value="2">
                                    <label class="form-check-label" for="cahai">Cả hai</label>
                                </div>
                            </div>
                        </div> -->
                        <div class="mb-4">
                                <label class="form-label" for="js-ckeditor">Hình ảnh</label>
                                <input class="form-control" type="file" id="file_anh" accept=".png, .jpg" required>
                                <span id="file_anh_fake" class="fake-file-input"></span>
                            </div>
                        <div class="mb-4">
                            <label for="ngaybd" class="form-label">Thời gian bắt đầu</label>
                            <input type="text" class="js-flatpickr form-control form-control-alt" id="ngaybd" name="ngaybd" placeholder="Ngày bắt đầu">
                        </div>
                        <div class="mb-4">
                            <label for="ngaykt" class="form-label">Thời gian kết thúc</label>
                            <input type="text" class="js-flatpickr form-control form-control-alt" id="ngaykt" name="ngaykt" placeholder="Ngày kết thúc">
                        </div>
                        <div class="mb-4 d-flex gap-4" style="flex-wrap: wrap">
                            <div class="space-x-2">
                                <label for="giatri" class="form-label">Giá trị (%)</label>

                                <!-- <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="phantram" name="dongia" value="1">
                                    <label class="form-check-label" for="phantram">%</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="dong" name="dongia" value="0">
                                    <label class="form-check-label" for="phantram">đồng</label>
                                </div> -->

                            </div>
                            <input type="number" class="form-control form-control-alt" name="giatri" id="giatri" placeholder="Giá trị">
                        </div>
                        <div class="d-flex align-items-center gap-5">
                            <label for="status" class="form-label">Trạng thái</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status" checked>
                                <label class="form-check-label" for="status"></label>
                            </div>
                        </div>
                        <div class="block-content block-content-full text-end">
                            <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-sm btn-primary add-promotion-element" id="btn-add-promotion">Lưu</button>
                            <button type="button" class="btn btn-sm btn-primary update-promotion-element" id="btn-update-promotion" data-id="">Cập nhật</button>
                        </div>
                    </form>
                    <div class="tab-pane" id="btabs-static-profile" role="tabpanel" aria-labelledby="btabs-static-profile-tab" tabindex="0">
                        <form id="form-upload" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label class="form-label" for="js-ckeditor">Nội dung</label>
                                <input class="form-control" type="file" id="file-cau-hoi" accept=".xlsx, .xls, .csv" required>
                            </div>
                            <div class="mb-4">
                                <em>Vui lòng soạn người dùng theo đúng định dạng. <a href="./public/filemau/danhsachsv.xls">Tải về file mẫu
                                        Excel</a></em>
                            </div>
                            <div class="mb-4 d-flex justify-content-between">
                                <button type="submit" class="btn btn-hero btn-primary" id="nhap-file"><i class="fa fa-cloud-arrow-up"></i> Thêm vào hệ thống</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>