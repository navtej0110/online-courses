@extends('layouts.admin.layout-left')

@section('title')
<a href="{{route('admin.question.list',['test_id' => $test_id])}}"><-</a> Questions : {{$test->name}}
@endsection

@section('footer-js')
<?php if(isset($data) && $data->is_locked == 1): ?>
<script type="text/javascript">
$(document).ready(function(){
    $("input").attr("disabled", true);
    $("textarea").attr("disabled", true);
    $("select").attr("disabled", true);
});
</script>
<?php endif; ?>
<script type="text/javascript">
$(document).ready(function(){
   $(document).on('click','.enable_disable',function(){
        if($(this).prop('checked') == true){
            $(".disabled :input").attr("disabled", false);
            $("#type").attr("disabled", false);
        }
        if($(this).prop('checked') == false){
            $(".disabled :input").attr("disabled", true);
            $("#type").attr("disabled", true);
        }
   });
});
</script>
<script type="text/javascript">
    var QUESTION_OPTIONS_ANSWERS = {
        app: '#question-answers',
        defaultSingleChoice: "",
        defaultTrueFalse: "",
        defaultYesNo: "",
        defaultmultipleChoice: "",
        getAlphabetFromNumber : function (_num) {
            var str = "";

            multiples = Math.ceil(_num / 26);
            _charAtCode = _num - ((multiples - 1) * 26)

            for (let i = 0; i < multiples; i++)
                str += String.fromCharCode(_charAtCode + 64);

            return str.toLowerCase();
        },
        disableEditing : function(){
            $(".disabled :input").attr("disabled", true);
            $('.enable_disable').prop('checked',false);
            $("#type").attr("disabled", true);
        },
        onLoad: function (option) {
            switch (option) {
                case 'true_false':
                    $(QUESTION_OPTIONS_ANSWERS.app).html(QUESTION_OPTIONS_ANSWERS.templates.trueFalse(QUESTION_OPTIONS_ANSWERS.defaultTrueFalse));
                    QUESTION_OPTIONS_ANSWERS.defaultTrueFalse != "" ? QUESTION_OPTIONS_ANSWERS.disableEditing() : "";
                    break;

                case 'yes_no':
                    $(QUESTION_OPTIONS_ANSWERS.app).html(QUESTION_OPTIONS_ANSWERS.templates.yesNo(QUESTION_OPTIONS_ANSWERS.defaultYesNo));
                    QUESTION_OPTIONS_ANSWERS.defaultTrueFalse != "" ? QUESTION_OPTIONS_ANSWERS.disableEditing() : "";
                    break;

                case 'single_choice':
                    $(QUESTION_OPTIONS_ANSWERS.app).html(QUESTION_OPTIONS_ANSWERS.templates.singleChoice(QUESTION_OPTIONS_ANSWERS.defaultSingleChoice));
                    QUESTION_OPTIONS_ANSWERS.defaultSingleChoice != "" ? QUESTION_OPTIONS_ANSWERS.disableEditing() : "";
                    break;

                case 'multiple_choice':
                    $(QUESTION_OPTIONS_ANSWERS.app).html(QUESTION_OPTIONS_ANSWERS.templates.multipleChoice(QUESTION_OPTIONS_ANSWERS.defaultmultipleChoice));
                    QUESTION_OPTIONS_ANSWERS.defaultmultipleChoice != "" ? QUESTION_OPTIONS_ANSWERS.disableEditing() : "";
                    break;

                default:
                    $(QUESTION_OPTIONS_ANSWERS.app).html('');
            }
        },
        getSelectedTypeForm: function (option) {
            switch ($(option).val()) {
                case 'true_false':
                    $(QUESTION_OPTIONS_ANSWERS.app).html(QUESTION_OPTIONS_ANSWERS.templates.trueFalse(QUESTION_OPTIONS_ANSWERS.defaultTrueFalse));
                    QUESTION_OPTIONS_ANSWERS.defaultTrueFalse != "" ? QUESTION_OPTIONS_ANSWERS.disableEditing() : "";
                    break;

                case 'yes_no':
                    $(QUESTION_OPTIONS_ANSWERS.app).html(QUESTION_OPTIONS_ANSWERS.templates.yesNo(QUESTION_OPTIONS_ANSWERS.defaultTrueFalse));
                    QUESTION_OPTIONS_ANSWERS.defaultTrueFalse != "" ? QUESTION_OPTIONS_ANSWERS.disableEditing() : "";
                    break;

                case 'single_choice':
                    $(QUESTION_OPTIONS_ANSWERS.app).html(QUESTION_OPTIONS_ANSWERS.templates.singleChoice(QUESTION_OPTIONS_ANSWERS.defaultSingleChoice));
                    QUESTION_OPTIONS_ANSWERS.defaultSingleChoice != "" ? QUESTION_OPTIONS_ANSWERS.disableEditing() : "";
                    break;

                case 'multiple_choice':
                    $(QUESTION_OPTIONS_ANSWERS.app).html(QUESTION_OPTIONS_ANSWERS.templates.multipleChoice(QUESTION_OPTIONS_ANSWERS.defaultmultipleChoice));
                    QUESTION_OPTIONS_ANSWERS.defaultmultipleChoice != "" ? QUESTION_OPTIONS_ANSWERS.disableEditing() : "";
                    break;

                default:
                    $(QUESTION_OPTIONS_ANSWERS.app).html('');
            }
        },
        templates: {
            singleChoice: function (defaultValues) {console.log(defaultValues);
                var html = `<div class="question-choice-option">
                    <div class="row col-sm-12 question-type">Type: Single Choice </div>`;
                
                if(defaultValues){
                    defaultValues = JSON.parse(defaultValues);
                }
                    
                for (var i = 1; i <= 4; i++) {;
                    if(defaultValues && defaultValues.length > 0){
                        var qa = defaultValues[i - 1];
                        var prefix = qa.prefix;
                        var option = qa.option;
                        var answer = qa.answer_boolean;
                    }else{
                        var prefix = "";
                        var option = "";
                        var answer = "";
                    }
                    html += `<div class="row col-sm-12 question-choice-option-group disabled">
                        <div class="col-md-2 col-sm-12">
                            <input class="form-control" type="text" placeholder="Prefix" name="types[single_choice][prefix][` + i + `]" value="`+prefix+`" />
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <input class="form-control" type="text" placeholder="Question Option" name="types[single_choice][option][` + i + `]" value="`+option+`" />
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <label>Answer </label>
                            <input class="" `+(answer == 1 ? "checked" : "")+` type="radio" name="types[single_choice][answer]" value="`+i+`" />
                        </div>
                    </div>`;
                }

                html += `</div>`;
                return html;
            },
            trueFalse: function (defaultValues) {
                if(defaultValues && defaultValues.length > 0){
                    defaultValues = JSON.parse(defaultValues);
                    var answer = defaultValues[0].answer_boolean;
                }else{
                    answer = "";
                }
                const html = `<div class="">
                <div class="row col-sm-12 question-type">Type: True / False </div>
                <div class="col-md-6 col-sm-12 question-type-option disabled">
                    <input type="radio" `+(answer == 1 ? "checked" : "")+` name="types[true_false][answer]" value="true" /> True
                </div>
                <div class="col-md-6 col-sm-12 question-type-option disabled">
                    <input type="radio" `+(answer == 0 ? "checked" : "")+` name="types[true_false][answer]" value="false" /> False
                </div>
            </div>`;
                return html;
            },
            yesNo: function (defaultValues) {
                if(defaultValues && defaultValues.length > 0){
                    defaultValues = JSON.parse(defaultValues);
                    var answer = defaultValues[0].answer_boolean;
                }else{
                    answer = "";
                }
                
                const html = `<div class="">
                <div class="row col-sm-12 question-type">Type: Yes / No </div>
                <div class="col-md-6 col-sm-12 question-type-option disabled">
                    <input type="radio" `+(answer == 1 ? "checked" : "")+` name="types[yes_no][answer]" value="true" /> Yes
                </div>
                <div class="col-md-6 col-sm-12 question-type-option disabled">
                    <input type="radio" `+(answer == 0 ? "checked" : "")+` name="types[yes_no][answer]" value="false" /> No
                </div>
            </div>`;
                return html;
            },
            multipleChoice: function (defaultValues) {
                
                if(defaultValues){
                    defaultValues = JSON.parse(defaultValues);
                }
                var html = `<div class="question-choice-option">
                    <div class="row col-sm-12 question-type">Type: Multi Choice </div>`;

                for (var i = 1; i <= 4; i++) {
                    if(defaultValues && defaultValues.length > 0){
                        var qa = defaultValues[i - 1];
                        var prefix = typeof qa.prefix == "undefined" ? "" : qa.prefix;
                        var option = qa.option;
                        var answer = qa.answer_boolean;
                    }else{
                        var prefix = "";
                        var option = "";
                        var answer = "";
                    }
                    html += `<div class="row col-sm-12 question-choice-option-group disabled">
                        <div class="col-md-2 col-sm-12">
                            <input class="form-control" type="text" placeholder="Prefix" name="types[multiple_choice][prefix][` + i + `]" value="`+prefix+`" />
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <input class="form-control" type="text" placeholder="Question Option" name="types[multiple_choice][option][` + i + `]" value="`+option+`" />
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <label>Answer </label>
                            <input class="" `+(answer == 1 ? "checked" : "")+` type="checkbox" name="types[multiple_choice][answer][`+i+`]" value="true" />
                        </div>
                    </div>`;
                }

                html += `</div>`;
                return html;
            },
        }
    }
</script>
<?php if(isset($data->id) && $data->id > 0): ?>
<script type="text/javascript">
var question_type = '<?php echo $data->type;?>';
if(question_type == 'true_false'){
    QUESTION_OPTIONS_ANSWERS.defaultTrueFalse = '<?php echo json_encode($data->options_answers);?>';
}else if(question_type == 'yes_no'){
    QUESTION_OPTIONS_ANSWERS.defaultYesNo = '<?php echo json_encode($data->options_answers);?>';
}else if(question_type == 'single_choice'){
    QUESTION_OPTIONS_ANSWERS.defaultSingleChoice = '<?php echo json_encode($data->options_answers);?>';
}else if(question_type == 'multiple_choice'){
    QUESTION_OPTIONS_ANSWERS.defaultmultipleChoice = '<?php echo json_encode($data->options_answers);?>';
}
QUESTION_OPTIONS_ANSWERS.onLoad(question_type); 
</script>
<?php endif; ?>
@include('helpers.tinymce')
<script type="text/javascript">
    $(document).ready(function () {
        $("#add-form").validate({
            rules: {
                name: {
                    required: true,
                },
                description: {
                    required: true,
                },
                type: {
                    required: true,
                }
            }
        });
    });
</script>
@endsection

@section('content')
<section id="basic-form-layouts">
    <div class="row match-height">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" id="basic-layout-form">Question Info</h4>
                    <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body collapse in">
                    <div class="card-block">
                        <form id="add-form" method="POST" action='{{ route("admin.question.addupdate", ["id"=>$id]) }}'>
                            @include('helpers.flash-message')
                            @csrf
                            <?php if (isset($data->id) && !empty(isset($data->id))): ?>
                                <input type="hidden" name="id" value="<?php echo $data->id; ?>" />
                            <?php endif; ?>
                                <input type="hidden" name="test_id" value="<?php echo $test_id; ?>" />
                            <div class="form-body">
                                <h4 class="form-section"><i class="icon-clipboard4"></i> Requirements</h4>

                                <div class="form-group">
                                    @error('name')
                                    <div class="alert alert-danger mb-2" role="alert">
                                        <strong>Oh snap!</strong> {{ $message }}
                                    </div>
                                    @enderror
                                    <label for="companyName">Name*</label>
                                    <input type="text" value="<?php echo isset($data->name) ? $data->name : Request::old('name'); ?>" id="companyName" class="form-control" placeholder="Course Name" name="name">
                                </div>

                                <div class="form-group">
                                    @error('description')
                                    <div class="alert alert-danger mb-2" role="alert">
                                        <strong>Oh snap!</strong> {{ $message }}
                                    </div>
                                    @enderror
                                    <label for="projectinput8">Description</label>
                                    <textarea id="projectinput8" rows="5" class="form-control" name="description" placeholder="About Course"><?php echo isset($data->description) ? $data->description : Request::old('description'); ?></textarea>
                                </div>

                                
                                <div class="form-group">
                                    <label for="projectinput6">Lock Question? <br />
                                        <span class="text-danger">If Locked it means that the question and answers are locked and can not be edited(changed) because it is visible to the front-end user and activity during this can not be seen by the user, but it can be removed(archived).so do not lock the question until you are not sure.</span> </label>
                                    <select id="is_locked" name="is_locked" class="form-control">
                                        <option value="0">Open</option>
                                        <option value="1" <?php echo isset($data->is_locked) && $data->is_locked == '1' ? "selected" : ""; ?>>Locked</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="projectinput5">Type </label> <?php echo isset($data) ? '( <input type="checkbox" class="enable_disable" /> Editable )' : "";?> 
                                    <select onchange="QUESTION_OPTIONS_ANSWERS.getSelectedTypeForm(this)" id="type" name="type" class="form-control">
                                        <option value="">--Select Type</option>
                                        <option value="single_choice" <?php echo isset($data->type) && $data->type == 'single_choice' ? "selected" : ""; ?>>Single Choice</option>
                                        <option value="true_false" <?php echo isset($data->type) && $data->type == 'true_false' ? "selected" : ""; ?>>True / False</option>
                                        <option value="yes_no" <?php echo isset($data->type) && $data->type == 'yes_no' ? "selected" : ""; ?>>Yes / No</option>
                                        <option value="multiple_choice" <?php echo isset($data->type) && $data->type == 'multiple_choice' ? "selected" : ""; ?>>Multiple Choice</option>
                                    </select>
                                </div>

                            </div>

                            <div id="question-answers" class="form-body">
                                
                            </div>
                            <?php if(isset($data) && $data->is_locked == 1): ?>    
                            <?php else: ?>
                            <div class="form-actions">
                                <button onclick="window.location.reload()" type="button" class="btn btn-warning mr-1">
                                    <i class="icon-cross2"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="icon-check2"></i> Save
                                </button>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection