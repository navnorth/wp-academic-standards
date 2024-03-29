<!-- Modal -->
<?php global $post; ?>
<div class="modal fade" id="addStandardModal" tabindex="-1" role="dialog" aria-labelledby="standardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="standardModalLabel">Add Standard</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      </div>
      <div id="add-standard" class="modal-body">
        <div id="add-core-standard" class="hidden-block">
          <div class="form-group">
            <label for="standard_name">Standard Name:</label>
            <input type="text" class="form-control" id="standard_name">
          </div>
          <div class="form-group">
            <label for="standard_url">Standard URL:</label>
            <input type="text" class="form-control" id="standard_url">
          </div>
        </div>
        <div id="add-sub-standard" class="hidden-block">
          <div class="form-group">
            <input type="hidden" id="standard_parent_id">
            <input type="hidden" id="sibling_count">
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
            <label for="">Label:</label>
            <select class="form-control" id="standard_label">
              
            </select>
          </div>
          <div class="form-group">
            <input type="hidden" id="standard_parent_id">
            <input type="hidden" id="sibling_count">
            <label for="">Prefix:</label>
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
        <button type="button" id="btnSaveStandards" class="btn btn-default btn-sm btn-primary" data-postid="<?php if (is_object($post)) echo $post->ID; ?>" data-dismiss="modal">Add</button>
      </div>
    </div>
  </div>
</div>