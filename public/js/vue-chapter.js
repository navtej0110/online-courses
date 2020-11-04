Vue.component('vue-chapter', {
    props: [
        'course',
        'module',
        'chapter',
        'main_quiz_link',
        'isMainQuizAvailable',
        'mini_quiz',
        'chapter_link'
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
                                        <b-collapse :id="'accordion-'+topic.id" accordion="my-accordion" role="tabpanel">
                                            <b-card-body>
                                                <ul>
                                                    <li><a :href="topic.link" class="accordion-list-style"> <div> <i class="fas fa-check-circle"></i> <h4> Watch the lesson</h4> </div> <p> {{topic.lesson_duration}} min</p> </a></li>
                                                    <li><a :href="topic.quizLink" class="accordion-list-style"> <div> <i class="fas fa-puzzle-piece"></i> <h4> Check your knowledge</h4> </div> </a></li>
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
                            <div class="col-lg-10">
                                <h3 class="right-title">{{this.chapter.name}}</h3>
                                <div class="sectionTwo"> <span> {{this.chapter.topics.length}} Lessons </span> <span> <i class="fa fa-clock Icons"> </i> {{this.chapter.duration / 60 > 1 ? Math.round(this.chapter.duration / 60)+' Hours ' + (this.chapter.duration % 60)+' Minutes' : this.chapter.duration }}</span> </div>
                                <div class="col-sm-12 row" style="margin:10px 0;padding:0;">{{this.chapter.description}}</div>
                            </div>
                            <div class="col-lg-2">
                                <div class="logo-right-img">
                                    <img src="https://lh3.googleusercontent.com/BO5E8aQODXSUk8cvfSRBTv0p4KdjSIh525VZF-_7PwkwU_bY_0Fo1wOcH3F1wvHMCNd-E3u3Z83v2czcCHeVm5uFMQvRohn19z0N-w" alt="Build your web presence">
                                </div>
                            </div>					
                        </div>
                        <div class="button-start">
                            <a :href="this.mini_quiz" class="btn btn-info start-learning"> Start Learning </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    `
});