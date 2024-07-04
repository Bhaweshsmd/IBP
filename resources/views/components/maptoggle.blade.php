<div class="toggle-sidebar listing-page">
    <div class="sidebar-layout-filters ">
        <div class="sidebar-header">
            <h2>Filter</h2>
            <a href="javascript:;" class="sidebar-closes"><i class="fa-regular fa-circle-xmark"></i></a>
        </div>
        <div class="sidebar-body-filter">
            <div class="listing-filter-group listing-item">
                <form action="#" autocomplete="off" class="listing-content">
                    <div class="sidebar-heading">
                        <h3>Advanced <span>Search</span></h3>
                        <p><a href="javascript:;">Clear All</a></p>
                    </div>
                    <div class="listing-search">
                        <div class="form-custom">
                            <input type="text" class="form-control" id="member_search1"
                                placeholder="What are you looking for">
                            <button class="btn"><span><i class="feather-search"></i></span></button>
                        </div>
                    </div>
                    <div class="accordion" id="accordionMain1">
                        <div class="card-header-new" id="headingOne">
                            <h5 class="filter-title">
                                <a href="javascript:void(0);" class="w-100" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Lesson type
                                    <span class="accordion-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample1">
                            <div class="card-body-chat">
                                <div class="sorting-select">
                                    <span><i class="feather-list"></i></span>
                                    <select class="form-control select">
                                        <option>Lesson 1</option>
                                        <option>Lesson 2</option>
                                        <option>Lesson 3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionMain2">
                        <div class="card-header-new" id="headingTwo">
                            <h5 class="filter-title">
                                <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    Location
                                    <span class="accordion-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample2">
                            <div class="card-body-chat">
                                <div class="sorting-select">
                                    <span><i class="feather-map-pin"></i></span>
                                    <select class="form-control select">
                                        <option>Select Location</option>
                                        <option>Location 1</option>
                                        <option>Location 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionMain3">
                        <div class="card-header-new" id="headingThree">
                            <h5 class="filter-title">
                                <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                    Radius
                                    <span class="accordion-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                            data-bs-parent="#accordionExample3">
                            <div class="card-body-chat">
                                <div class="filter-range">
                                    <input type="text" class="input-range">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionMain4">
                        <div class="card-header-new" id="headingFour">
                            <h5 class="filter-title">
                                <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                    Price Range
                                    <span class="accordion-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                            data-bs-parent="#accordionExample4">
                            <div class="card-body-chat range-price">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-custom">
                                            <input type="text" class="form-control" placeholder="Enter Min Price">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-custom">
                                            <input type="text" class="form-control" placeholder="Enter Max Price">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionMain5">
                        <div class="card-header-new" id="headingFive">
                            <h5 class="filter-title">
                                <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                    Guests
                                    <span class="accordion-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive"
                            data-bs-parent="#accordionExample5">
                            <div class="card-body-chat">
                                <div id="checkBoxes5">
                                    <div class="selectBox-cont">
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span>0-2
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span> 2-4
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span> 4-5
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span> More than 5+
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionMain6">
                        <div class="card-header-new" id="headingSix">
                            <h5 class="filter-title">
                                <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                                    Reviews
                                    <span class="accordion-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix"
                            data-bs-parent="#accordionExample6">
                            <div class="card-body-chat">
                                <div id="checkBoxes4">
                                    <div class="selectBox-cont">
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled text-warning"></i>
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled "></i>
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled "></i>
                                            <i class="fas fa-star filled"></i>
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled text-warning"></i>
                                            <i class="fas fa-star filled "></i>
                                            <i class="fas fa-star filled"></i>
                                            <i class="fas fa-star filled"></i>
                                        </label>
                                        <div class="view-content">
                                            <div class="viewall-Two">
                                                <label class="custom_check w-100">
                                                    <input type="checkbox" name="username">
                                                    <span class="checkmark"></span>
                                                    <i class="fas fa-star filled text-warning"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                    <i class="fas fa-star filled"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionMain7">
                        <div class="card-header-new" id="headingSeven">
                            <h5 class="filter-title">
                                <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#collapseSeven" aria-expanded="true"
                                    aria-controls="collapseSeven">
                                    Availability
                                    <span class="accordion-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven"
                            data-bs-parent="#accordionExample7">
                            <div class="card-body-chat datepicker-calendar">
                                <div id="checkBoxes7">
                                    <div class="selectBox-cont">
                                        <div class="card-body">
                                            <div id="calendar-doctor" class="calendar-container"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionMain8">
                        <div class="card-header-new" id="headingEight">
                            <h5 class="filter-title">
                                <a href="javascript:void(0);" class="w-100 collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#collapseEight" aria-expanded="true"
                                    aria-controls="collapseEight">
                                    Amenities
                                    <span class="accordion-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight"
                            data-bs-parent="#accordionExample8">
                            <div class="card-body-chat">
                                <div id="checkBoxes8">
                                    <div class="selectBox-cont">
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span>Waiting Area
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span>Waiting Area
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span>Clothes
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span>Wi-fi
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" name="category">
                                            <span class="checkmark"></span>Parking
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="search-btn btn w-100 btn-primary">
                        <span><i class="feather-search me-2"></i></span>Search Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
