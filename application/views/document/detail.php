  <div class="nk-content ">
      <div class="container-fluid">
          <div class="nk-content-inner">
              <div class="nk-content-body">
                  <div class="nk-block-head nk-block-head-lg wide-sm">
                      <div class="nk-block-head-content">
                          <div class="nk-block-head-sub"><a class="back-to" href="<?= site_url('home') ?>"><em class="icon ni ni-arrow-left"></em><span>Back to home</span></a></div>
                      </div>
                  </div>
                  <div class="card card-bordered card-preview">
                      <div class="card-inner">
                          <div id="accordion" class="accordion">
                              <div class="accordion-item">
                                  <a href="#" class="accordion-head" data-bs-toggle="collapse" data-bs-target="#accordion-item-1">
                                      <h6 class="title"><?= $document->file_name; ?></h6>
                                      <span class="accordion-icon"></span>
                                  </a>
                                  <div class="accordion-body collapse show" id="accordion-item-1" data-bs-parent="#accordion">
                                      <div class="accordion-inner">
                                          <p><?= $document->description; ?></p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- <div class="nk-block-head nk-block-head-sm">
                      <div class="nk-block-between">
                          <div class="nk-block-head-content">
                              <h3 class="nk-block-title page-title"><?= $document->file_name; ?></h3>
                              <div class="nk-block-des text-soft">
                                  <p><?= $document->type_name; ?></p>
                              </div>
                          </div>
                      </div>
                  </div> -->
                  <div class="nk-block">
                      <div class="row g-gs">
                          <div class="col-xxl-6">
                              <div class="row g-gs">
                                  <div class="col-md-12">
                                      <iframe src="<?= base_url('uploads/' . $document->file) ?>" style="width:100%; height:100vh;" frameborder="0" allowfullscreen></iframe>
                                  </div><!-- .col -->

                                  <!-- <div class="col-md-4">
                                      <div class="card card-full">
                                          <div class="card-inner-group">
                                              <div class="card-inner">
                                                  <div class="card-title-group">
                                                      <div class="card-title">
                                                          <h7 class="title"><b>MCI Chatbot - Chat with PDF</b></h7>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="card-inner card-inner-md">
                                                  <div class="card-inner" style="text-align: center;">
                                                      <img src="<?= base_url('assets') ?>/images/ai.png" alt="Chatbot Image" style="width: 60px; height: 60px; margin-bottom: 20px;">
                                                      <h6 class="card-title"><small class="text-dark">MCI Chatbot - Powered by ChatGPT</small></h6>
                                                      <h6 class="card-title"><small class="text-dark"><b>Chat, Learn, Earn</b></small></h6>
                                                      <p class="card-text mt-4">With MCI Chatbot, your documents are becoming intelligent. It works great for commanding operations on PDFs and obtain precise information from them using natural language. Try chatting with your files right now.</p>
                                                      <a href="javascript:void(0)" id="chatNowButton" class="btn btn-sm btn-danger mt-3">Chat Now</a>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div> -->

                              </div><!-- .row -->
                          </div><!-- .col -->
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>