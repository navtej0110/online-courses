Vue.component('vue-questionsbank', {
    props: [
        'quizTypes',
        'displayTypes',
        'allowQuestions',
        'chapterId',
        'topicId',
        'id',
        'getUrl',
        'postUrl',
        'deleteUrl',
        'isLocked'
    ],
    data: function () {
        return {
            errors: [],
            serverError: "",
            questions: [],
            isSaving: 0
        }
    },
    mounted: function () {
        this.onLoad();
    },
    methods: {
        onLoad() {
            var url = this.getUrl;
            var _self = this;

            if (url) {
                axios({
                    method: 'post',
                    url: url,
                })
                .then(response => {
                    if (response.data.success == '1') {
                        _self.questions = [];
                        for (i in response.data.payload) {
                            _self.questions.push(response.data.payload[i]);
                        }
                        //console.log(response.data.payload);
                    } else {
                        alert(response.data.error);
                    }
                })
                .catch(error => {
                    alert('There is Some error please try Later!');
                });
            }else{
                _self.questions = [];
            }
        },
        reload() {
            window.location.reload();
        },
        addQuestionRow() {
            this.questions.push({
                name: "",
                description: '',
                question_type: '',
                is_locked: 0,
                quiz_type: '',
                question_information: '',
                display_type: '',
                id: 0,
                is_archive: 0,
                options: []
            });
            //console.log(this.questions);
        },
        addOptionsRow(index, event) {
            const selected = event.target.value;
            this.questions[index].question_type = selected;

            switch (selected) {
                case 'true_false':
                    this.questions[index].options = [{
                            name: "",
                            description: '',
                            answer_boolean: '',
                            images: 0,
                            prefix: '',
                            question_information: '',
                            is_archive: 0,
                            id: 0
                        }];
                    break;
            }
        },
        addSingleChoiceRow(index) {
            this.questions[index].options.push({
                name: "",
                description: '',
                answer_boolean: '',
                images: 0,
                prefix: '',
                question_information: '',
                is_archive: 0,
                id: 0
            });
        },
        removeQuestionRow(index) {
            //console.log(this.questions[index]);
            //this.questions.splice(index, 1)
            var _self = this;
            var r = confirm("Sure You Want to Delete Question " + (index + 1) + "!");
            if (r == true) {
                if (this.questions[index].id > 0) {
                    axios({
                        method: 'post',
                        url: _self.deleteUrl,
                        data: {id: this.questions[index].id}
                    })
                            .then(response => {
                                if (response.data.success == '1') {
                                    this.$delete(_self.questions, index)
                                    alert('Removed Successfully!');
                                } else {
                                    alert(response.data.error);
                                }
                            })
                            .catch(error => {
                                alert('There is Some error please try Later!');
                            });
                }else{
                   this.$delete(this.questions, index) 
                }
                // ajax delete
                //this.$delete(this.questions, index)
            } else {
                return false;
            }
        },
        removeOptionRow(index, indexOption) {
            //console.log(this.questions[index].options);
            //this.questions.splice(index, 1)
            var r = confirm("Sure You Want to Delete Option " + (indexOption + 1) + " for Question " + (index + 1) + "!");
            if (r == true) {
                this.$delete(this.questions[index].options, indexOption)
            } else {
                return false;
            }
        },
        addAnswerRow(index) {

        },
        
        radioDataSet(index,indexOption){
            for(var i in this.questions){
                for(var j in this.questions[i].options){
                    if( index == i ){
                        // set selected to 0 for all.
                        this.questions[i].options[j].answer_boolean = 0;
                    }
                }
            }
        },
        checkForm: function (e) {
            var _self = this;
            this.errors = [];
            this.serverError = "";
            this.isSaving = 0;
            // validaions.
            for (i in this.questions) {
                if (!this.questions[i].name) {
                    this.errors.push('Question ' + (parseInt(i) + 1) + ': Name is Required');
                }

                if (!this.questions[i].question_type) {
                    this.errors.push('Question ' + (parseInt(i) + 1) + ': Question Type is Required');
                }

                if (!this.questions[i].quiz_type) {
                    this.errors.push('Question ' + (parseInt(i) + 1) + ': Quiz Type is Required');
                }

                if (!this.questions[i].display_type) {
                    this.errors.push('Question ' + (parseInt(i) + 1) + ': Display Type is Required');
                }

                if (this.questions[i].options.length <= 0) {
                    this.errors.push('Question ' + (parseInt(i) + 1) + ': Options are Required');
                } else {
                    for (j in this.questions[i].options) {
                        if (!this.questions[i].options[j].name) {
                            this.errors.push('Question ' + (parseInt(i) + 1) + ': Option ' + (parseInt(j) + 1) + ': Name is Required');
                        }
                        if (!this.questions[i].options[j].prefix) {
                            this.errors.push('Question ' + (parseInt(i) + 1) + ': Option ' + (parseInt(j) + 1) + ': Prefix is Required');
                        }
                    }
                }
            }

            if (this.errors.length > 0) {
                var err = '';
                for (i = 0; i < this.errors.length; i++) {
                    err += this.errors[i] + "\n";
                }

                e.preventDefault();
                alert(err);
                return false;
            }

            if (!this.errors.length) {
                this.generating = true;

                var url = this.postUrl;

                _self.isSaving = 1;

                axios({
                    method: 'post',
                    url: url,
                    data: _self.questions
                })
                        .then(response => {
                            _self.isSaving = 0;
                            if (response.data.success == '1') {
                                _self.onLoad();
                                alert('Question/Options Saved Successfully!');
                            } else {
                                alert(response.data.error);
                            }
                        })
                        .catch(error => {
                            _self.isSaving = 0;
                            alert('There is Some error please try Later!');
                        });
            }

            e.preventDefault();
        }

    },
    template: `
            <div class="col-lg-12 row">
                <div class="col-lg-12" style="margin-bottom:10px;">
                    <span v-if="allowQuestions == 'multiple'" class="add-pointer" @click="addQuestionRow"><i class="icon-square-plus"></i> Add Question</span>
                </div>
        
                <form @submit="checkForm">
                <div v-for="(question,index) in questions" :key="index" class="col-lg-12 question-container">
                    <div class="col-lg-12">
                        <div class="col-lg-3 m-b-20"><label><b>No.{{index + 1}}</b> Name*</label>: 
                            <input class="form-control" v-model="question.name" v-bind:name="'question[' + index + '][name]'" type="text" />
                            <input v-model="question.id" v-bind:name="'question[' + index + '][id]'" type="hidden" />
                        </div>
                        <div class="col-lg-2 m-b-20"><label>Question Type*</label>
                            <select class="form-control" v-on:change="addOptionsRow(index,$event)" v-model="question.question_type" v-bind:name="'question[' + index + '][question_type]'">
                                <option value="">--select--</option>
                                <option value="single_choice">Single Choice</option>
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="true_false">True False</option>
                            </select>    
                        </div>
                        <div class="col-lg-2 m-b-20"><label>Quiz Type*</label>:
                            <select class="form-control" v-model="question.quiz_type" v-bind:name="'question[' + index + '][quiz_type]'">
                                <option v-for="(qt, iqt) in quizTypes" v-bind:value="qt.value">{{qt.name}}</option>
                            </select>    
                        </div>
                        <div class="col-lg-3 m-b-20"><label>Display Type*</label>: 
                            <select class="form-control" v-model="question.display_type" v-bind:name="'question[' + index + '][display_type]'">
                                <option value="">--select--</option>
                                <option v-for="(dt, idt) in displayTypes" v-bind:value="dt.value">{{dt.name}}</option>
                            </select></div>
                        <div class="col-lg-2" style="margin:35px 0 10px 0;"><span v-if="allowQuestions == 'multiple'" class="delete-pointer" v-on:click="removeQuestionRow(index)"><i class="icon-square-cross"></i> Delete Question</span>  </div>
                        <div class="col-lg-12 m-b-20"><label>Question Information</label>
                            <textarea class="form-control" v-model="question.question_information" v-bind:name="'question[' + index + '][question_information]'"></textarea>
                        </div>
                        <div class="col-lg-12 m-b-20"><label>Description</label> 
                            <textarea class="form-control" v-model="question.description" v-bind:name="'question[' + index + '][description]'"></textarea>
                        </div>
                    </div>
                    
                    <!-- single Questions Options -->
                    <div class="col-lg-12">
                    <div v-if="question.question_type == 'single_choice'" class="col-lg-12 option-container">
                        <div class="col-sm-12 m-b-20">
                            <h4>Options: Single Choice : {{question.options.length}}</h4>
                        </div>
                        <div v-for="(option,indexOption) in question.options" :key="indexOption" class="col-lg-12 row">
                            <div class="col-lg-7 m-b-20"><label><b>No.{{indexOption + 1}}</b> Name*</label>: 
                                <input class="form-control" v-model="option.name" v-bind:name="'question[option][' + indexOption + '][name]'" type="text" />
                            </div>
                            <div class="col-lg-3 m-b-20"><label>Prefix*</label>: 
                                <input class="form-control" v-model="option.prefix" v-bind:name="'question[option][' + indexOption + '][prefix]'" type="text" />
                            </div>
                            <div class="col-lg-2 m-b-20 t-a-c"><label>Answer*</label>: 
                                <input @click="radioDataSet(index,indexOption)" class="form-control" v-model="option.answer_boolean" v-bind:name="'question[option][' + index + '][' + indexOption + '][answer_boolean]'" value="1" type="radio" />
                            </div>
                            
                            <div class="col-lg-12 m-b-20"><label>Description</label> 
                                <textarea class="form-control" v-model="option.description" v-bind:name="'question[option][' + indexOption + '][description]'"></textarea>
                            </div>
                            <div class="col-lg-2"><span class="delete-pointer" v-on:click="removeOptionRow(index,indexOption)"><i class="icon-circle-minus"></i> Delete Option. {{indexOption + 1}}</span></div>
                            <div class="col-sm-12"><hr /></div>
                        </div>
                        <div class="col-sm-12 m-b-20">
                            <span class="add-pointer" @click="addSingleChoiceRow(index)"><i class="icon-circle-plus"></i> Add Options</span>
                        </div>
                    </div>
                    
                    <!-- Multichoice Questions Options -->
                    <div v-if="question.question_type == 'multiple_choice'" class="col-lg-12 option-container">
                        <div class="col-sm-12 m-b-20">
                            <h4>Options: Multiple Choice : {{question.options.length}}</h4>
                        </div>
                        <div v-for="(option,indexOption) in question.options" :key="indexOption" class="col-lg-12 row">
                            <div class="col-lg-7 m-b-20"><label><b>No.{{indexOption + 1}}</b> Name*</label> 
                                <input class="form-control" v-model="option.name" v-bind:name="'question[option][' + indexOption + '][name]'" type="text" />
                            </div>
                            <div class="col-lg-3 m-b-20"><label>Prefix*</label>
                                <input class="form-control" v-model="option.prefix" v-bind:name="'question[option][' + indexOption + '][prefix]'" type="text" />
                            </div>
                            <div class="col-lg-2 m-b-20 t-a-c"><label>Answer*</label>
                                <input class="form-control" v-model="option.answer_boolean" v-bind:name="'question[option][' + index + '][' + indexOption + '][answer_boolean]'" value="1" type="checkbox" />
                            </div>
                            <div class="col-lg-12 m-b-20"><label>Description</label>
                                <textarea class="form-control" v-model="option.description" v-bind:name="'question[option][' + indexOption + '][description]'"></textarea>
                            </div>
                            <div class="col-lg-2 m-b-20"><span class="delete-pointer" v-on:click="removeOptionRow(index,indexOption)"><i class="icon-circle-minus"></i> Delete Option. {{indexOption + 1}}</span></div>
                            <div class="col-sm-12"><hr /></div>
                        </div>
                        <div class="col-sm-12 m-b-20">
                            <span class="add-pointer" @click="addSingleChoiceRow(index)"><i class="icon-circle-plus"></i> Add Options</span>
                        </div>
                    </div>
    
                    <!-- True False Questions Options -->
                    <div v-if="question.question_type == 'true_false'" class="col-lg-12 option-container">
                        <div class="col-sm-12 m-b-20">
                            <h4>Options: True False</h4>
                        </div>
                        <div v-for="(option,indexOption) in question.options" :key="indexOption" class="col-lg-12 row">
                            <div class="col-lg-3 m-b-20"><label><b>No.{{indexOption + 1}}</b> Name*</label>
                                <input class="form-control" v-model="option.name" v-bind:name="'question[option][' + indexOption + '][name]'" type="text" />
                            </div>
                            <div class="col-lg-3 m-b-20"><label>Prefix*</label>
                                <input class="form-control" v-model="option.prefix" v-bind:name="'question[option][' + indexOption + '][prefix]'" type="text" />
                            </div>
                            <div class="col-lg-2 m-b-20 t-a-c"><label>True*</label> 
                                <input class="form-control" v-model="option.answer_boolean" v-bind:name="'question[option][' + index + '][answer_boolean]'" type="radio" />
                            </div>
                            <div class="col-lg-2 m-b-20 t-a-c"><label>False*</label>
                                <input class="form-control" v-model="option.answer_boolean" v-bind:name="'question[option][' + index + '][answer_boolean]'" value="false" type="radio" />
                            </div>
                            <div class="col-lg-2 m-b-20 t-a-c"><label>Answer</label><br />
                                <span v-if="option.answer_boolean == '1'"><b>True</b></span>
                                <span v-if="option.answer_boolean == '0'"><b>False</b></span>
                            </div>
                            <div class="col-lg-12 m-b-20"><label>Description</label>
                                <textarea class="form-control" v-model="option.description" v-bind:name="'question[option][' + indexOption + '][description]'"></textarea>
                            </div>
                            <div class="col-lg-2 m-b-20"><span class="delete-pointer" v-on:click="removeOptionRow(index,indexOption)"><i class="icon-circle-minus"></i> Delete Option</span></div>
                        </div>
                    </div>
                    </div>
                </div>
                <div v-if="questions.length > 0" class="col-lg-12 m-t-20" style="margin-bottom:10px;">
                    <span v-if="allowQuestions == 'multiple'" class="add-pointer" @click="addQuestionRow"><i class="icon-square-plus"></i> Add Question</span>
                </div>
                <div  class="col-sm-12 row" style="margin-top:15px;">
                    <div class="col-sm-2 row">
                        <button v-if="questions.length > 0 && isSaving == 0" type="submit" class="btn btn-success btn-block">Success</button>
                    </div>
                    <div class="col-sm-2">
                        <button v-if="questions.length > 0 && isSaving == 0" type="button" @click="reload" class="btn btn-warning btn-block">Reset</button>
                    </div>
                    <div class="col-sm-12">
                        <span v-if="isSaving == 1" class="text-success">Saving Please Wait...</span>
                    </div>
                </div>
                </form>
            </div>`
})