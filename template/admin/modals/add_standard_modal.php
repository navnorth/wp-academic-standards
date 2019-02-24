<!-- Modal -->
<?php global $post; ?>
<div class="modal fade" id="addStandardModal" tabindex="-1" role="dialog" aria-labelledby="standardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="standardModalLabel">Add Standard</h4>
      </div>
      <div id="add-standard" class="modal-body">
        <div id="add-sub-standard" class="hidden-block">
          <div class="form-group">
            <input type="hidden" id="standard_parent_id">
            <label for="">Standard Title:</label>
            <input type="text" class="form-control" id="standard_title">
          </div>
          <div class="form-group">
            <label for="">Standard URL:</label>
            <input type="text" class="form-control" id="standard_url">
          </div>
        </div>
        <div id="add-standard-notation" class="hidden-block">
          <div class="form-group">
            <input type="hidden" id="standard_parent_id">
            <label for="">Standard Notation:</label>
            <input type="text" class="form-control" id="standard_notation">
          </div>
          <div class="form-group">
            <label for="">Description:</label>
            <input type="text" class="form-control" id="description">
          </div>
          <div class="form-group">
            <label for="">Comment:</label>
            <input type="text" class="form-control" id="comment">
          </div>
          <div class="form-group">
            <label for="">Notation URL:</label>
            <input type="text" class="form-control" id="notation_url">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnSaveStandards" class="btn btn-default btn-sm" data-postid="<?php echo $post->ID; ?>" data-dismiss="modal">Add</button>
      </div>
    </div>
  </div>
</div>