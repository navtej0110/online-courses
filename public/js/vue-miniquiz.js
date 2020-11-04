Vue.component('vue-miniquiz', {
    props: [
        'course',
        'module',
        'chapter',
        'main_quiz_link',
        'topic',
        'isMainQuizAvailable',
        'questions',
        'chapter_link',
        'quiz',
        'submitMiniQuiz',
        'is_main_quiz_avialable',
        'topic_link',

    ],
    data: function () {
        return {
            errors: [],
            questionForm: [],
            serverError: "",
            isLoading: 0,
            isSaving : 0,
            quiz_successfull:0
        }
    },
    methods: {
        checkForm: function (e) {
            console.log(this.topic.mini_quiz);
            var _self = this;
            
            _self.isSaving = 1;
            
            axios({
                method: 'post',
                url: _self.submitMiniQuiz,
                data: this.topic.mini_quiz
            })
                    .then(response => {
                        if (response.data.success == '1') {
                            _self.quiz_successfull = 1;
                        } else if (response.data.success == '0'){
                            alert(response.data.error);
                            _self.isSaving = 0;
                        }
                    })
                    .catch(error => {
                        alert('There is Some error please try Later!');
                    });
            e.preventDefault();
        },
        radioCheck : function(questionIndex, optionIndex){
            for(var i in this.topic.mini_quiz){
                if(i == questionIndex){
                    for(var j in this.topic.mini_quiz[i].options_answers){
                        console.log(this.topic.mini_quiz[i].options_answers[j].option);
                        this.topic.mini_quiz[i].options_answers[j].answer = 0;
                    }
                }
            }
            this.topic.mini_quiz[questionIndex].options_answers[optionIndex].answer = 1;
            console.log(this.topic.mini_quiz);
        },
        getfilterData: function($event,filterindex,filtervalue){
                alert('Please complete all Topics');
        }
    },
    mounted: function () {
        console.log(this.topic);
    },
    template: `
    <section class="topic-overview disable_text">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <div class="topic-nav">
                        <div class="top-title-nav"> <a :href="this.chapter_link"> <h2 class="nav-title">Topic Overview</h2> </a> </div>
                        <div class="nav-accord-sec">
                            <div class="row">
                                <div class="col-lg-6"> <h2 class="nav-title">Lessons</h2> </div>
                                <div class="col-lg-6"> <p class="progress-text">{{chapter.topics.length}}</p> </div>
                            </div>
                            <div id="accordion">
                                
                                <!-- one topic -->
                                <div role="tablist">
                                    
                                    <b-card v-for="(topic,topicIndex) in chapter.topics" :key="topicIndex" no-body class="">
                                        <b-card-header header-tag="header" class="" role="tab">
                                            <a class="card-link" block href="javascript:void(0)" v-b-toggle="'accordion-' + topic.id" variant="info">{{topicIndex + 1}}. {{topic.title}}</a>
                                        </b-card-header>
                                        
                                        <b-collapse v-if="topic.visible != 'visible'" :id="'accordion-'+topic.id" accordion="my-accordion" role="tabpanel">
                                            <b-card-body>
                                                <ul>
                                                    <li>
                                                        <a :href="topic.link" class="accordion-list-style"> <div> 
                                                        <i v-if="topic.visible != 'visible'" class="fas fa-check-circle"></i> 
                                                        <h4>Watch the lesson</h4> </div> <p> {{topic.lesson_duration}} min</p> </a>
                                                        </li>
                                                    <li><a :href="topic.quizLink" class="accordion-list-style"> <div> <i class="fas fa-puzzle-piece"></i> <h4> Check your knowledge </h4> </div> </a></li>
                                                </ul>
                                            </b-card-body>
                                        </b-collapse>
    
                                        <b-collapse v-if="topic.visible == 'visible'" :id="'accordion-'+topic.id" visible accordion="my-accordion" role="tabpanel">
                                            <b-card-body>
                                                <ul>
                                                    <li>
                                                        <a :href="topic.link" class="accordion-list-style"> <div> 
                                                        <i v-if="topic.visible == 'visible'" class="fas fa-check-circle text-success"></i> 
                                                        <h4>Watch the lesson</h4> </div> <p> {{topic.lesson_duration}} min</p> </a>
                                                        </li>
                                                    <li><a :href="topic.quizLink" class="accordion-list-style"> <div> <i class="fas fa-puzzle-piece text-success"></i> <h4> Check your knowledge <i v-if="topic.user_mini_quiz.length > 0" class="far fa-check-circle text-success"></i></h4> </div> </a></li>
                                                </ul>
                                            </b-card-body>
                                        </b-collapse>
    
                                    </b-card>

                                </div>
                                <!-- one topic ends -->
                            </div>
                        </div>  
                        <div v-if="this.isMainQuizAvailable == '1'" class="skip-button">
                            <a :href="this.main_quiz_link" class="btn btn-success skip-to-quiz"> <i class="fa fa-arrow-right"> </i> &nbsp;Skip to quiz </a>
                        </div>
                        <div  v-if="this.isMainQuizAvailable == '0'" class="skip-button">
                            <a href="javascript:void(0)" onclick="alert('Please complete all topics to Access Main Quiz!')" class="btn btn-danger skip-to-quiz"> <i class="fa fa-arrow-right"> </i> &nbsp;Skip to quiz </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 ">
                    <div class="Build-right-sec">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="right-title">{{this.topic.title}}</h3>
                                
                                <div class="col-sm-12 row">
                                    <p>Check Your Knowledge</p>
                                </div>
                                <div>
                                    {{this.topic.check_your_knowledge}}
                                </div>
                                <hr />
                                
                                <form @submit="checkForm">
                                    <div class="question-sec" v-for="(question,questionIndex) in this.topic.mini_quiz" :key="questionIndex">
                                        <div class="question-header">
                                            <h2> Question {{questionIndex + 1}}</h2>
                                            <p>{{question.name}}</p>
                                            <p> {{question.question_information}}</p>
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
    
                                    <div v-if="this.topic.already_answered == 0">
                                        <center v-if="this.quiz_successfull == 0">
                                            <button v-if="this.isSaving == 0" type="submit" class="btn btn-info">Submit</button>
                                            <span v-if="this.isSaving == 1" class="text-success">Submitting Answers...</span>
                                        </center>
                                    </div>
                                    <div>
                                        <center>
                                            <h5 v-if="this.topic.already_answered == '1' || this.quiz_successfull == 1">
                                            <hr /><br />Good Job Quiz Completed <i class="far fa-thumbs-up text-success"></i>
                                            </h5>
                                            <h5 v-if="this.isMainQuizAvailable == '0' && this.topic.already_answered == '1' && this.topic_link">
                                                <br /><b><a class="text-danger" :href="this.topic_link">Go To Next Topic <i class="fas fa-arrow-right"></i></a></b>
                                            </h5>
                                            <h5 v-if="this.isMainQuizAvailable == '1'">
                                                <b><a class="text-success" :href="this.main_quiz_link">Go To Main Quiz <i class="fas fa-arrow-right"></i></a></b>
                                            </h5>
                                            </center>
                                        </div>
                                </form>
                            </div>                  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    `
});