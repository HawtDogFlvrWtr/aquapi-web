<?php
include 'header.php';
?>                    
                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-9">
                                <div class="page-title-box">
                                    <h4 class="page-title">Tank Maintenance Calendar</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        <div class="row">
                            <div class="col-xl-12">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div id="calendar"></div>
                                            </div> <!-- end col -->
                                            <div class="col-lg-3">
                                            </div> <!-- end col -->

                                        </div>  <!-- end row -->
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->

                                <!-- Add New Event MODAL -->
                                <div class="modal fade" id="event-modal" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header pr-4 pl-4 border-bottom-0 d-block">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">Event Details</h4>
                                            </div>
                                            <div class="modal-body pt-3 pr-4 pl-4">
                                            </div>
                                            <div class="text-right pb-4 pr-4">
                                                <button type="button" class="btn btn-light " data-dismiss="modal">Close</button>
                                            </div>
                                        </div> <!-- end modal-content-->
                                    </div> <!-- end modal dialog-->
                                </div>
                                <!-- end modal-->

                                <!-- Modal Add Category -->
                                <div class="modal fade" id="add-category" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header border-bottom-0 d-block">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">Add a category</h4>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form>
                                                    <div class="form-group">
                                                        <label class="control-label">Category Name</label>
                                                        <input class="form-control form-white" placeholder="Enter name" type="text" name="category-name"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Choose Category Color</label>
                                                        <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                                            <option value="primary">Primary</option>
                                                            <option value="success">Success</option>
                                                            <option value="danger">Danger</option>
                                                            <option value="info">Info</option>
                                                            <option value="warning">Warning</option>
                                                            <option value="dark">Dark</option>
                                                        </select>
                                                    </div>

                                                </form>

                                                <div class="text-right">
                                                    <button type="button" class="btn btn-light " data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary ml-1   save-category" data-dismiss="modal">Save</button>
                                                </div>

                                            </div> <!-- end modal-body-->
                                        </div> <!-- end modal-content-->
                                    </div> <!-- end modal dialog-->
                                </div>
                                <!-- end modal-->
                            </div>
                            <!-- end col-12 -->
                        </div> <!-- end row -->


		    </div> <!-- container-fluid -->
<?php
  include 'footer.php';
?>
