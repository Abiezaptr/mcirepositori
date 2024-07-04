<!-- content @s -->
<div class="nk-content p-0">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-fmg-body-content">
                <div class="nk-block-head nk-block-head-sm">
                    <h4 class="mt-2"><strong>File Management</strong></h4>
                    <div class="nk-block-between position-relative mt-4">
                        <div class="nk-block-head-content">
                            <ul class="nk-block-tools g-1">
                                <li class="d-lg-none">
                                    <a href="#" class="btn btn-trigger btn-icon search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a>
                                </li>
                                <li class="d-lg-none">
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-trigger btn-icon" data-bs-toggle="dropdown"><em class="icon ni ni-plus"></em></a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <ul class="link-list-opt no-bdr">
                                                <li><a href="#file-upload" data-bs-toggle="modal"><em class="icon ni ni-upload-cloud"></em><span>Upload File</span></a></li>
                                                <li><a href="#"><em class="icon ni ni-file-plus"></em><span>Create File</span></a></li>
                                                <li><a href="#"><em class="icon ni ni-folder-plus"></em><span>Create Folder</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-lg-none me-n1"><a href="#" class="btn btn-trigger btn-icon toggle" data-target="files-aside"><em class="icon ni ni-menu-alt-r"></em></a></li>
                            </ul>
                        </div>
                        <div class="search-wrap px-2 d-lg-none" data-search="search">
                            <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                            <button class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
                        </div><!-- .search-wrap -->
                    </div>
                </div>
                <div class="nk-fmg-listing nk-block-lg">
                    <div class="nk-block-head-xs">
                        <!-- Form Filter -->
                        <form action="" method="post" id="filterdoc">
                            <div class="row gy-4">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-left">
                                                <em class="icon ni ni-search"></em>
                                            </div>
                                            <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Search document">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <select name="category_id" id="category_id" class="form-control">
                                                <option value="all">All Category</option>
                                                <?php foreach ($categories as $category) : ?>
                                                    <option value="<?= $category->category_id ?>"><?= $category->category_name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="btn-group" role="group">
                                        <?php if ($this->session->userdata('role') == 1) : ?>
                                            <a href="<?= site_url('document') ?>" class="btn btn-outline-light"><em class="icon ni ni-upload-cloud"></em>&nbsp; Upload</a>
                                        <?php elseif ($this->session->userdata('role') == 2) : ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div><!-- .nk-block-head -->
                    <div class="tab-content mt-3">
                        <div class="tab-pane active" id="file-grid-view">
                            <div class="nk-files nk-files-view-grid">
                                <div class="nk-files-list">
                                    <?php foreach ($documents as $d) : ?>
                                        <div class="nk-file-item nk-file">
                                            <div class="nk-file-info">
                                                <div class="nk-file-title">
                                                    <div class="nk-file-icon">
                                                        <a class="nk-file-icon-link" href="#">
                                                            <span class="nk-file-icon-type">
                                                                <img src="<?= base_url('assets') ?>/images/pdf.png" alt="" data-bs-toggle="modal" data-bs-target="#modalZoom<?= $d->document_id ?>">
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="nk-file-name">
                                                        <div class="nk-file-name-text">
                                                            <a href="<?= site_url('document/view/' . $d->document_id) ?>" class="title"><?= $d->file_name ?></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="nk-file-desc">
                                                    <li class="size"><?= $d->category_name ?></li>
                                                </ul>
                                                <?php if ($this->session->userdata('role') == 1) : ?>
                                                    <div class="nk-file-actions">
                                                        <div class="dropdown">
                                                            <a href="javascript:void(0)" class="dropdown-toggle btn btn-sm btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-plain no-bdr">
                                                                    <li><a href="<?php echo site_url('document/update/' . $d->document_id); ?>"><em class="icon ni ni-pen"></em><span>Update</span></a></li>
                                                                    <li><a href="<?= site_url('document/remove/' . $d->document_id) ?>"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php elseif ($this->session->userdata('role') == 2) : ?>

                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div><!-- .nk-files -->
                        </div><!-- .tab-pane -->
                    </div>
                    <?php if ($this->session->userdata('role') == 1) : ?>
                        <div class="card card-preview mt-5">
                            <table class="table table-tranx">
                                <thead>
                                    <tr class="tb-tnx-head">
                                        <th class="tb-tnx-id"><span class="">Filename</span></th>
                                        <th class="tb-tnx-info">
                                            <span class="tb-tnx-desc d-none d-sm-inline-block">
                                                <span>Category</span>
                                            </span>
                                            <span class="tb-tnx-date d-md-inline-block d-none">
                                                <span class="d-none d-md-block">
                                                    <span>Upload date</span>
                                                    <span>Last view</span>
                                                </span>
                                            </span>
                                        </th>
                                        <th class="tb-tnx-amount is-alt">
                                            <span class="tb-tnx-total">File size</span>
                                            <span class="tb-tnx-status d-none d-md-inline-block">Status</span>
                                        </th>
                                        <th class="tb-tnx-action">
                                            <span>&nbsp;</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($documents as $data) : ?>
                                        <tr class="tb-tnx-item">
                                            <td class="tb-tnx-id">
                                                <img src="<?= base_url('assets') ?>/images/pdf.png" alt="Revenue News Sales" width="30">
                                                &nbsp; <span class=" title"><?= $data->file_name ?></span>
                                            </td>
                                            <td class="tb-tnx-info">
                                                <div class="tb-tnx-desc">
                                                    <span class="title"><?= $data->category_name ?></span>
                                                </div>
                                                <div class="tb-tnx-date">
                                                    <span class="date"><?= date('d-m-Y', strtotime($data->created_at)) ?></span>
                                                    <span class="date"><?= date('d-m-Y', strtotime($data->last_viewed)) ?></span>
                                                </div>
                                            </td>
                                            <td class="tb-tnx-amount is-alt">
                                                <div class="tb-tnx-total">
                                                    <span class="amount"><?= $data->file_size ?></span>
                                                </div>
                                                <div class="tb-tnx-status">
                                                    <span class="badge badge-dot bg-success">Open</span>
                                                </div>
                                            </td>
                                            <td class="tb-tnx-action">
                                                <div class="dropdown">
                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-xs">
                                                        <ul class="link-list-plain">
                                                            <li><a href="#">Change</a></li>
                                                            <li><a href="<?= site_url('document/remove/' . $data->document_id) ?>">Remove</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php elseif ($this->session->userdata('role') == 2) : ?>

                    <?php endif; ?>
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<!-- content @e -->

<!-- modal details -->
<?php foreach ($documents as $d) : ?>
    <div class="modal fade zoom" tabindex="-1" id="modalZoom<?= $d->document_id ?>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $d->file_name ?></h5>
                    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><small><i><b><?= $d->category_name ?></b></i></small></h6>
                        </div>
                        <div class="col-md-6">
                            <h6><small><i><b><em class="icon ni ni-clock"></em> Upload time : <?= date('d F Y', strtotime($d->created_at)) ?></b></i></small></h6>
                        </div>
                    </div>
                    <hr>
                    <p><?= $d->description ?></p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="<?= site_url('document/view/' . $d->document_id) ?>" class="btn btn-primary">Open</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>


<!-- kode filter -->
<script>
    $(document).ready(function() {
        function filterDocuments() {
            var keyword = $('#keyword').val();
            var category_id = $('#category_id').val();

            console.log("Filter triggered");
            console.log("Keyword: " + keyword);
            console.log("Category ID: " + category_id);

            $.ajax({
                url: '<?= site_url("files/search_documents") ?>',
                method: 'POST',
                data: {
                    keyword: keyword,
                    category_id: category_id
                },
                success: function(response) {
                    console.log("Response received");
                    console.log(response);

                    var documents = JSON.parse(response);
                    var documentHtml = '';

                    if (documents.length > 0) {
                        documents.forEach(function(document) {
                            documentHtml += `
                            <div class="nk-file-item nk-file">
                                <div class="nk-file-info">
                                    <div class="nk-file-title">
                                        <div class="nk-file-icon">
                                            <a class="nk-file-icon-link" href="#">
                                                <span class="nk-file-icon-type">
                                                    <img src="<?= base_url('assets') ?>/images/pdf.png" alt="" data-bs-toggle="modal" data-bs-target="#modalZoom${document.value}">
                                                </span>
                                            </a>
                                        </div>
                                        <div class="nk-file-name">
                                            <div class="nk-file-name-text">
                                                <a href="<?= site_url('document/view/') ?>${document.value}" class="title">${document.label}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="nk-file-desc">
                                        <li class="size">${document.category_name}</li>
                                    </ul>
                                    <?php if ($this->session->userdata('role') == 1) : ?>
                                        <div class="nk-file-actions">
                                            <div class="dropdown">
                                                <a href="javascript:void(0)" class="dropdown-toggle btn btn-sm btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <ul class="link-list-plain no-bdr">
                                                        <li><a href="<?= site_url('document/update/') ?>${document.value}"><em class="icon ni ni-pen"></em><span>Update</span></a></li>
                                                        <li><a href="<?= site_url('document/remove/') ?>${document.value}"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif ($this->session->userdata('role') == 2) : ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        `;
                        });
                    } else {
                        documentHtml = '<p>No documents found</p>';
                    }

                    $('.nk-files-list').html(documentHtml);
                },
                error: function(xhr, status, error) {
                    console.error("Error occurred: " + status + " " + error);
                }
            });
        }

        $('#keyword').on('input', filterDocuments);
        $('#category_id').on('change', filterDocuments);
    });
</script>