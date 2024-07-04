   <div class="nk-content ">
       <div class="container-fluid">
           <div class="nk-content-inner">
               <div class="nk-content-body">
                   <div class="nk-block">
                       <div class="card">
                           <div class="card-aside-wrap">
                               <div class="card-inner card-inner-lg">
                                   <div class="nk-block-head nk-block-head-lg">
                                       <div class="nk-block-between">
                                           <div class="nk-block-head-content">
                                               <h4 class="nk-block-title">Document Information</h4>
                                               <div class="nk-block-des">
                                                   <p>Basic info, like your name and address, that you use on Nio Platform.</p>
                                               </div>
                                           </div>
                                           <div class="nk-block-head-content align-self-start d-lg-none">
                                               <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                           </div>
                                       </div>
                                   </div><!-- .nk-block-head -->
                                   <div class="nk-block">
                                       <div class="nk-data data-list">
                                           <div class="data-head">
                                               <h6 class="overline-title">Basics</h6>
                                           </div>
                                           <div class="data-item">
                                               <div class="data-col">
                                                   <span class="data-label">Document ID</span>
                                                   <span class="data-value"><?= $document->document_id ?></span>
                                               </div>
                                               <div class="data-col data-col-end"><span class="data-more disable"></span></div>
                                           </div>
                                           <div class="data-item">
                                               <div class="data-col">
                                                   <span class="data-label">Document Title</span>
                                                   <span class="data-value"><?= $document->file_name ?></span>
                                               </div>
                                               <div class="data-col data-col-end"><span class="data-more disable"></span></div>
                                           </div>
                                           <div class="data-item">
                                               <div class="data-col">
                                                   <span class="data-label">Document Type</span>
                                                   <span class="data-value text-uppercase fw-bold"><?= $document->file_type ?></span>
                                               </div>
                                               <div class="data-col data-col-end"><span class="data-more disable"></span></div>
                                           </div>
                                           <div class="data-item">
                                               <div class="data-col">
                                                   <span class="data-label">Category</span>
                                                   <span class="data-value"><?= $document->category_name ?></span>
                                               </div>
                                               <div class="data-col data-col-end"><span class="data-more disable"></span></div>
                                           </div>
                                           <div class="data-item" data-bs-toggle="modal" data-bs-target="#profile-edit" data-tab-target="#address">
                                               <div class="data-col">
                                                   <span class="data-label">Description</span>
                                                   <span class="data-value"><?= nl2br($document->description) ?></span>
                                               </div>
                                               <div class="data-col data-col-end"><a class="link link-primary">Change</a></div>
                                           </div><!-- data-item -->
                                           <div class="data-item">
                                               <div class="data-col">
                                                   <span class="data-label">Upload date</span>
                                                   <span class="data-value"><?= date("d M, Y", strtotime($document->created_at)) ?></span>
                                               </div>
                                               <div class="data-col data-col-end"><span class="data-more disable"></span></div>
                                           </div><!-- data-item -->
                                           <div class="data-item">
                                               <div class="data-col">
                                                   <span class="data-label">Status</span>
                                                   <span class="data-value"><span class="badge rounded-pill bg-success"><?= $document->status ?></span></span>
                                               </div>
                                               <div class="data-col data-col-end"><span class="data-more disable"></span></div>
                                           </div>
                                       </div><!-- data-list -->
                                   </div><!-- .nk-block -->
                               </div>
                           </div><!-- .card-aside-wrap -->
                       </div><!-- .card -->
                   </div><!-- .nk-block -->
               </div>
           </div>
       </div>
   </div>

   <div class="modal fade" role="dialog" id="profile-edit">
       <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
           <div class="modal-content">
               <a href="#" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
               <div class="modal-body modal-body-lg">
                   <ul class="nk-nav nav nav-tabs">
                       <li class="nav-item">
                           <a class="nav-link active" data-bs-toggle="tab" href="#address">Update Document</a>
                       </li>
                   </ul><!-- .nav-tabs -->
                   <div class="tab-content">
                       <div class="tab-pane active" id="address">
                           <form action="<?= site_url('document/update_process') ?>" method="post">
                               <input type="hidden" name="document_id" value="<?= $document->document_id ?>">
                               <div class="row gy-4">
                                   <div class="col-md-12 mb-4">
                                       <div class="form-group">
                                           <label class="form-label">Description</label>
                                           <textarea name="description" class="form-control form-control-lg"><?= $document->description ?></textarea>
                                       </div>
                                   </div>
                                   <div class="col-12 d-flex justify-content-end mt-3">
                                       <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                           <li>
                                               <button type="submit" class="btn btn-lg btn-primary">submit</button>
                                           </li>
                                           <li>
                                               <a href="#" data-bs-dismiss="modal" class="btn btn-lg btn-light text-dark">Cancel</a>
                                           </li>
                                       </ul>
                                   </div>
                               </div>
                           </form>
                       </div><!-- .tab-pane -->
                   </div><!-- .tab-content -->
               </div><!-- .modal-body -->
           </div><!-- .modal-content -->
       </div><!-- .modal-dialog -->
   </div>