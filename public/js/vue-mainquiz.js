Vue.component('vue-mainquiz', {
    props: [
        'course',
        'module',
        'chapter',
        'questions',
        'passingScore',
        'submitMainQuiz',
        'main_quiz_link',
    ],
    data: function () {
        return {
            errors: [],
            questionForm: [],
            serverError: "",
            isLoading: 0,
            isSaving : 0,
            quiz_successfull:0,
            previous_score : '',
            result:'',
            resubmit:0
        }
    },
    methods: {
        tryAgain: function(e){
            location.reload();
        },
        checkForm: function (e) {
            console.log(this.questions);
            
            var _self = this;
            
            _self.isSaving = 1;
             
            axios({
                method: 'post',
                url: _self.submitMainQuiz,
                data: this.questions
            })
            .then(response => {
                if (response.data.success == '1') {
                    _self.quiz_successfull = 1;
                    _self.isSaving = 0;
                    _self.previous_score = response.data.percentage > 0 ? response.data.percentage+'%' : 'Zero';
                    //console.log(_self.passingScore);
                    //console.log(response.data.percentage);
                    if( parseInt(_self.passingScore) <= parseInt(response.data.percentage)){
                        _self.result = "pass";
                    }else{
                        _self.result = 'fail';
                    }
                    
                    if(response.data.wrong.length > 0){
                        for(var i in response.data.wrong){
                            for(var j in _self.questions){
                                if(_self.questions[j].id == response.data.wrong[i]){
                                    _self.questions[j].correct = '0';
                                }
                            }
                        }
                    }
                    
                    if(response.data.correct.length > 0){
                        for(var i in response.data.correct){
                            for(var j in _self.questions){
                                if(_self.questions[j].id == response.data.correct[i]){
                                    _self.questions[j].correct = '1';
                                }
                            }
                        }
                    }
                    
                    console.log(_self.questions);
                } else if (response.data.success == '0'){
                    alert(response.data.error);
                    _self.isSaving = 0;
                }
                
                _self.resubmit = 1;
            })
            .catch(error => {
                alert('There is Some error please try Later!');
            });
            
            e.preventDefault();
        },
        radioCheck : function(questionIndex, optionIndex){
            for(var i in this.questions){
                if(i == questionIndex){
                    for(var j in this.questions[i].options_answers){
                        this.questions[i].options_answers[j].answer = 0;
                    }
                }
            }
            this.questions[questionIndex].options_answers[optionIndex].answer = 1;
            console.log(this.questions);
        }
    },
    mounted: function () {
        //console.log(this.questions);
    },
    template: `
            <section style="padding: 35px 0;" class="disable_text">
                <div class="container-fluid">
                    <div v-if="questions.length > 0" class="assessment-question-container">
                        <form @submit="checkForm">
                        <div class="question-sec" v-for="(question, questionIndex) in questions" :key="questionIndex">
                            <div class="question-header">
                                <h2 class="text-success" v-if="question.correct == '1'"><i class="far fa-check-circle"></i> Well done, that's correct!</h2>
                                <h2 class="text-danger" v-if="question.correct == '0'"><i class="far fa-times-circle"></i> That's not quite right!</h2>
                                <h2>Question {{questionIndex + 1}}</h2>
                                <p>{{question.question_information}}</p>
                                <p> {{question.description}}</p>
                            </div>
                            
                            <!-- multiple choice question -->     
                            <ul v-if="question.question_type == 'multiple_choice'">
                                <li v-for="(option, optionIndex) in question.options_answers">
                                    <input type="checkbox" :id="option.id" v-model="question.options_answers[optionIndex].answer" value="1" v-bind:name="'question[' + questionIndex + '][options_answers]['+optionIndex+'][answer][]'">						
                                    <label :for="option.id"><span class="letter"> {{option.prefix}} </span> {{option.option}}</label>						
                                    <div class="check"></div>
                                </li>
                            </ul>
    
                            <!-- single choice option --> 
                            <ul v-if="question.question_type == 'single_choice'">
                                <li v-for="(option, optionIndex) in question.options_answers">
                                    <input type="radio" :id="option.id" v-model="question.options_answers[optionIndex].answer" value="1" v-on:change="radioCheck(questionIndex, optionIndex)" v-bind:name="'question[' + questionIndex + '][options_answers][answer]'">						
                                    <label :for="option.id"><span class="letter"> {{option.prefix}} </span> {{option.option}}</label>						
                                    <div class="check"></div>
                                </li>
                            </ul>
    
                            <!-- true false -->
                            <ul v-if="question.question_type == 'true_false'">
                                <li v-for="(option, optionIndex) in question.options_answers">
                                    <input value="1" :id="option.id+'-true'" v-bind:name="'question[' + questionIndex + '][options_answers][answer]'" type="radio" v-model="question.options_answers[optionIndex].answer" v-on:change="" />
                                    <label :for="option.id+'-true'"><span class="letter"> T </span> True</label>
                                    <div class="check"></div>
                                </li>
                                <li v-for="(option, optionIndex) in question.options_answers">
                                    <input value="0" :id="option.id+'-false'" v-bind:name="'question[' + questionIndex + '][options_answers][answer]'" type="radio" v-model="question.options_answers[optionIndex].answer" v-on:change="" />
                                    <label :for="option.id+'-false'"><span class="letter"> F </span> False</label>
                                    <div class="check"></div>
                                </li>
                            </ul>
    
                        </div>
                        <center><span class="text-success" v-if="this.result == 'pass'">Congrats You have Passed The Quiz :)<br /><br /></span></center>
                        <center><span class="text-danger" v-if="this.result == 'fail'">Sorry You have not Passed The Quiz :(<br /><br /></span></center>
                        <center><span v-if="this.previous_score != ''">Your Score : {{this.previous_score}}<br /><br /></span></center>
                        <button v-if="this.isSaving == 0 && this.resubmit == 0 && this.result != 'pass'" type="submit" class="btn btn--primary btn-info active"> Submit Answers </button>
                        <button v-on:click="tryAgain" v-if="this.resubmit == 1 && this.result != 'pass'" type="button" class="btn btn--primary btn-warning active"> Try Again?</button>
                        <center><span v-if="this.isSaving == 1">Submitting Answers ...</span></center>
                        </form>
                    </div>
                </div>  
            </section>`
});