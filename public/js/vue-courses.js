Vue.component('vue-courses', {
    props: [
        'courses',
        'courseLink',
       
    ],
    data: function () {
        return {
            errors: [],
            serverError: "",
            isLoading: 0,
            cartcount:0,
            
        }
    },
    methods: {
    addToCart: function(course_id,user_id,price) {
        if(user_id!=0)
        {
              axios.post('/public/course/add-to-cart/'+course_id+'/'+user_id+'/'+price)
                .then(response => {
                    console.log(response);
                     this.cartcount=response.data    
                 })
                .catch(error => {
                  console.log(response);
                })
        }
        else
        {
            alert('Please login for adding Course enrollment')
        }
      
      
    }
},

    mounted: function () {
    },
    template: `<div class="row">
            <div class="col-lg-2">
                <div id="left-sidebar">
                    <div class="filter-wrapper">
                        <div class="side-course-list">
                            <h4 class="sidebar-top-title"> All Categories </h4>
                            <form action="">				
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1" name="example1" value="1">
                                    <label class="custom-control-label" for="customCheck1">Data and Tech</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck2" name="example1">
                                    <label class="custom-control-label" for="customCheck2">Digital Marketing</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck3" name="example1" value="2">
                                    <label class="custom-control-label" for="customCheck3"> Career Development</label>
                                </div>
                            </form>
                        </div>
                        <div class="side-course-list">
                            <h4 class="sidebar-top-title"> Course length </h4>
                            <form action="">				
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck4" name="example1">
                                    <label class="custom-control-label" for="customCheck4">Under 2 hours</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck5" name="example1">
                                    <label class="custom-control-label" for="customCheck5">2–10 hours</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck6" name="example1">
                                    <label class="custom-control-label" for="customCheck6"> 11–20 hours </label>
                                </div>
                            </form>
                        </div>
                        <div class="side-course-list">
                            <h4 class="sidebar-top-title"> Certification </h4>
                            <form action="">				
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck7" name="example1">
                                    <label class="custom-control-label" for="customCheck7">Free certificate</label>
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck8" name="example1">
                                    <label class="custom-control-label" for="customCheck8"> Paid certificate</label>
                                </div>						
                            </form>
                        </div>
                    </div>
                    <div class="filter-button">
                        <button type="submit" class="btn"><i class="fa fa-retweet"> </i> Reset filters</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-10">
                <div id="right-side-bar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="total-courses"> Results: {{courses.length}} courses </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href='/public/course/cart' id="cart-count" class="btn btn-secondary"><span class="badge badge-light">{{cartcount}}</span> Go to Course Enrollment</a>
                        </div>
                    </div>
                    
                   
                    <div class="row">
                        <!-- course info -->
                            <div v-for="(course,index) in courses" :key="index" class="col-lg-4">
				  <div class="course-list-card">
				  
                                    <div class="card-image"><img class="card-img-top" src="https://lh3.googleusercontent.com/21Mlc_bfmIP34V4MgJMtr1S9sGbxNGVdj7ncT_jmiQNAhvqJNYwWhnOdKuY2h57SpOuaOk_aF5dAnrz0w4tbDLVy0wxZHJCUQC3y=s500" alt="Card image" style="width:100%"></div>
                                        <div class="card-body">
                                            <div class="card-content-top">
                                                <h4 class="card-title">{{course.name}}</h4>

                                                <!--<span class="short-text"> Created by Google</span>-->
                                                <div class="course-card-info">
                                                    <span><i class="fa fa-bars Icons"> </i> Modules: {{course.modules.length}}</span>
                                                    <span><i class="fa fa-clock Icons"> </i>  40 H</span>
                                                </div>
                                                <div class="course-card-info">
                                                    <ul class="course_features list-unstyled">
                                                        <li>Include Certificate <i v-if="course.include_certificate == 1" class="text-success fas fa-check"></i><i v-if="course.include_certificate == 0" class="text-danger fas fa-times"></i></li>
                                                        <li>Beginner <i v-if="course.is_beginner == 1" class="text-success fas fa-check"></i><i v-if="course.is_beginner == 0" class="text-danger fas fa-times"></i></li>
                                                        <li>Intermediate <i v-if="course.is_intermediate == 1" class="text-success fas fa-check"></i><i v-if="course.is_intermediate == 0" class="text-danger fas fa-times"></i></li>
                                                        <li>Advanced <i v-if="course.is_advanced == 1" class="text-success fas fa-check"></i><i v-if="course.is_advanced == 0" class="text-danger fas fa-times"></i></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class ="cart" v-if="course.price > 0">
                                                    </div>
                                            <div class="card-content-bottom" >
                                            
                                                <center>
                                                    <p v-if="course.price <= 0" >Start: Free</p>
                                                    <p v-if="course.price > 0" >Price: {{course.price}}$</p>
                                                </center> <a v-if="course.payment_status== 1" v-bind:href="course.link" >Go for detail <i class="fa fa-arrow-right right-arrow"> </i></a>
                                          </div>	
                                          <button v-if="course.payment_status== 0" @click="addToCart(course.id,course.user_id,course.price)" class="btn btn-success">Add Course for enrollment</button>
                                             
 
                                        </div>

                                    
				</div>
                            </div>
                            <!-- course info ends -->
                            
                                                
                    </div>
                </div>

            </div>

        </div>`
});