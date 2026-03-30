<!-- Global Modal -->
<div class="modal fade" id="global-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"><!-- jQuery Populated --></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div><!-- /.modal-header -->
            <div class="modal-body">
                <!-- /jQuery Populated -->
            </div><!-- /.modal-body -->
            <div class="modal-footer">
                <a class="button -tertiary" data-dismiss="modal" href="#">Close</a>
            </div><!-- /.modal-footer -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="viewing-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <div class="pl-2 pr-2 pl-sm-5 pr-sm-5">
                    <h5 class="modal-title">Arrange Viewing</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div><!-- /.modal-header -->
            <div class="modal-body">
                @widget('arrange_viewing')
            </div><!-- /.modal-body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- #viewing-modal -->
