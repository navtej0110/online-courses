Vue.component('vue-topic', {
    props: [
        'course',
        'module',
        'chapter',
        'isMainQuizAvailable',
        'topic',
        'quiz',
        'chapter_link',
        'main_quiz_link'
    ],
    data: function () {
        return {
            errors: [],
            serverError: "",
            isLoading: 0
        }
    },
    mounted: function () {
        console.log(this.chapter);
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
                                                    <li><a :href="topic.quizLink" class="accordion-list-style"> <div> <i class="fas fa-puzzle-piece"></i> <h4> Check your knowledge</h4> </div> </a></li>
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
                                                    <li><a :href="topic.quizLink" class="accordion-list-style"> <div> <i class="fas fa-puzzle-piece text-success"></i> <h4> Check your knowledge</h4> </div> </a></li>
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
                                
                                <div v-if="topic.video_1" class="col-sm-12 row">
                                    <iframe style="margin-bottom:20px;" width="100%" height="315" volume="0"
                                        :src="this.topic.video_1">
                                    </iframe>
                                </div>
                                
                                <div>
                                    <center><b-button v-b-toggle.collapse-1 variant="primary"><i class="fab fa-readme"></i> View Transcript</b-button></center>
                                    <b-collapse id="collapse-1" class="">
                                      <b-card>
                                        {{this.topic.content}}
                                      </b-card>
                                    </b-collapse>
                                </div>
                                <hr />
                                <br />
                                <center><div class="button-start">
                                    <a :href="topic.quizLink" class="btn btn-info"> Check Your Knowledge </a>
                                </div></center>
                                <br />
                                <h3>Key learnings</h3>
                                <div class="col-sm-12 row" style="margin:10px 0;padding:0;">{{this.topic.key_learnings}}</div>
                            </div>					
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    `
});