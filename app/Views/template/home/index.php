<div class="geex-content__wrapper">
    <div class="geex-content__section-wrapper">
        <div class="geex-content__feature mb-40">

            <!-- CARD START -->
            <div class="geex-content__feature__card">
                <div class="geex-content__feature__card__text">
                    <p class="geex-content__feature__card__subtitle">Memory</p>
                    <h4 class="geex-content__feature__card__title">200TB</h4>
                    <span class="geex-content__feature__card__badge">+2.5%</span>
                </div>
                <div class="geex-content__feature__card__img">
                    <img src="<?= $this->Url->assetUrl('assets/img/feature/feature-2.svg') ?>" alt="feature" />
                </div>
            </div>
            <!-- CARD END -->
            <!-- CARD START -->
            <div class="geex-content__feature__card">
                <div class="geex-content__feature__card__text">
                    <p class="geex-content__feature__card__subtitle">Visitors</p>
                    <h4 class="geex-content__feature__card__title">87,245k</h4>
                    <span class="geex-content__feature__card__badge danger-color">-4.4%</span>
                </div>
                <div class="geex-content__feature__card__img">
                    <img src="<?= $this->Url->assetUrl('assets/img/feature/feature-3.svg') ?>" alt="feature" />
                </div>
            </div>
            <!-- CARD END -->
            <!-- CARD START -->
            <div class="geex-content__feature__card">
                <div class="geex-content__feature__card__text">
                    <p class="geex-content__feature__card__subtitle">New Users</p>
                    <h4 class="geex-content__feature__card__title">4,750</h4>
                    <span class="geex-content__feature__card__badge">+2.5%</span>
                </div>
                <div class="geex-content__feature__card__img">
                    <img src="<?= $this->Url->assetUrl('assets/img/feature/feature-1.svg') ?>" alt="feature" />
                </div>
            </div>
            <!-- CARD END -->
        </div>

        <!-- SERVER REQUEST CHART START -->
        <div class="geex-content__section geex-content__server-request mb-40">
            <div class="geex-content__section__header">
                <div class="geex-content__section__header__title-part">
                    <h4 class="geex-content__section__header__title">Server Request</h4>
                </div>
            </div>
            <div class="geex-content__section__content">
                <div id="line-chart" class="server-request-chart"></div>
            </div>
        </div>
        <!-- SERVER REQUEST CHART END -->

        <div class="geex-content__double-column mb-40">

            <!-- COLUMN CHART START -->
            <div class="geex-content__section geex-content__visitor-count">
                <div class="geex-content__section__header">
                    <div class="geex-content__section__header__title-part">
                        <h4 class="geex-content__section__header__title">Visitors</h4>
                    </div>
                    <div class="geex-content__section__header__content-part">
                        <div class="geex-content__section__header__btn">
                            <a href="#" class="geex-content__section__header__link">
                                View More
                            </a>
                        </div>
                    </div>
                </div>
                <div class="geex-content__section__content">
                    <div class="geex-content__visitor-count__number">
                        <h2 class="geex-content__visitor-count__number__title">98,425k</h2>
                        <div class="geex-content__visitor-count__number__text">
                            <span class="geex-content__visitor-count__number__subtitle">+2.5%</span>
                            <p class="geex-content__visitor-count__number__desc">Than last week</p>
                        </div>
                    </div>
                    <div id="column-chart"></div>
                </div>
            </div>
            <!-- COLUMN CHART END -->
            <!-- PIE CHART START -->
            <div class="geex-content__section geex-content__chat-summary">
                <div class="geex-content__section__header">
                    <div class="geex-content__section__header__title-part">
                        <h4 class="geex-content__section__header__title">Chart Summary</h4>
                    </div>
                    <div class="geex-content__section__header__content-part">
                        <div class="geex-content__section__header__btn">
                            <a href="#" class="geex-content__section__header__link">
                                Download Report
                            </a>
                        </div>
                    </div>
                </div>
                <div class="geex-content__section__content">
                    <div id="pie-chart"></div>
                </div>
            </div>
            <!-- PIE CHART END -->
        </div>

        <!-- TESTIMONIALS START -->
        <div class="geex-content__section geex-content__section--transparent geex-content__review">
            <div class="geex-content__section__header">
                <div class="geex-content__section__header__title-part">
                    <h4 class="geex-content__section__header__title">User Review</h4>
                    <p class="geex-content__section__header__subtitle">Eum fuga consequuntur ut et.</p>
                </div>
                <div class="geex-content__section__header__content-part">
                    <div class="geex-content__section__header__btn geex-content__section__header__swiper-btn">
                        <div class="swiper-btn swiper-btn-prev">
                            <i class="uil-arrow-left"></i>
                        </div>
                        <div class="swiper-btn swiper-btn-next">
                            <i class="uil-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="geex-content__section__content">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="geex-content__review__single">
                                <div class="geex-content__review__single__header">
                                    <div class="geex-content__review__single__header__img">
                                        <img src="<?= $this->Url->assetUrl('assets/img/testimonial/1.svg') ?>" alt="User" />
                                    </div>
                                    <div class="geex-content__review__single__header__text">
                                        <h4 class="geex-content__review__single__header__title">John Doe</h4>
                                        <p class="geex-content__review__single__header__subtitle">4 days ago</p>
                                    </div>
                                </div>
                                <div class="geex-content__review__single__content">
                                    <p class="geex-content__review__single__content__text">Ab architecto provident ex accusantium deserunt. Aut aspernatur deleniti sit maiores ut id cum accusamus. Beatae n</p>
                                </div>
                                <div class="geex-content__review__single__bottom">
                                    <a href="#" class="geex-content__review__single__btn danger-color">Archive</a>
                                    <a href="#" class="geex-content__review__single__btn success-color">Accept</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="geex-content__review__single">
                                <div class="geex-content__review__single__header">
                                    <div class="geex-content__review__single__header__img">
                                        <img src="<?= $this->Url->assetUrl('assets/img/testimonial/2.svg') ?>" alt="User" />
                                    </div>
                                    <div class="geex-content__review__single__header__text">
                                        <h4 class="geex-content__review__single__header__title">Kevin Hunt</h4>
                                        <p class="geex-content__review__single__header__subtitle">6 days ago</p>
                                    </div>
                                </div>
                                <div class="geex-content__review__single__content">
                                    <p class="geex-content__review__single__content__text">Ab architecto provident ex accusantium deserunt. Aut aspernatur deleniti sit maiores ut id cum accusamus. Beatae n</p>
                                </div>
                                <div class="geex-content__review__single__bottom">
                                    <a href="#" class="geex-content__review__single__btn danger-color">Archive</a>
                                    <a href="#" class="geex-content__review__single__btn success-color">Accept</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="geex-content__review__single">
                                <div class="geex-content__review__single__header">
                                    <div class="geex-content__review__single__header__img">
                                        <img src="<?= $this->Url->assetUrl('assets/img/testimonial/3.svg') ?>" alt="User" />
                                    </div>
                                    <div class="geex-content__review__single__header__text">
                                        <h4 class="geex-content__review__single__header__title">Isabbelle</h4>
                                        <p class="geex-content__review__single__header__subtitle">7 days ago</p>
                                    </div>
                                </div>
                                <div class="geex-content__review__single__content">
                                    <p class="geex-content__review__single__content__text">Ab architecto provident ex accusantium deserunt. Aut aspernatur deleniti sit maiores ut id cum accusamus. Beatae n</p>
                                </div>
                                <div class="geex-content__review__single__bottom">
                                    <a href="#" class="geex-content__review__single__btn success-color">Publish <i class="uil-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="geex-content__review__single">
                                <div class="geex-content__review__single__header">
                                    <div class="geex-content__review__single__header__img">
                                        <img src="<?= $this->Url->assetUrl('assets/img/testimonial/4.svg') ?>" alt="User" />
                                    </div>
                                    <div class="geex-content__review__single__header__text">
                                        <h4 class="geex-content__review__single__header__title">Kevin Hunt</h4>
                                        <p class="geex-content__review__single__header__subtitle">6 days ago</p>
                                    </div>
                                </div>
                                <div class="geex-content__review__single__content">
                                    <p class="geex-content__review__single__content__text">Ab architecto provident ex accusantium deserunt. Aut aspernatur deleniti sit maiores ut id cum accusamus. Beatae n</p>
                                </div>
                                <div class="geex-content__review__single__bottom">
                                    <a href="#" class="geex-content__review__single__btn danger-color">Archive</a>
                                    <a href="#" class="geex-content__review__single__btn success-color">Accept</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="geex-content__review__single">
                                <div class="geex-content__review__single__header">
                                    <div class="geex-content__review__single__header__img">
                                        <img src="<?= $this->Url->assetUrl('assets/img/testimonial/2.svg') ?>" alt="User" />
                                    </div>
                                    <div class="geex-content__review__single__header__text">
                                        <h4 class="geex-content__review__single__header__title">Isabbelle</h4>
                                        <p class="geex-content__review__single__header__subtitle">7 days ago</p>
                                    </div>
                                </div>
                                <div class="geex-content__review__single__content">
                                    <p class="geex-content__review__single__content__text">Ab architecto provident ex accusantium deserunt. Aut aspernatur deleniti sit maiores ut id cum accusamus. Beatae n</p>
                                </div>
                                <div class="geex-content__review__single__bottom">
                                    <a href="#" class="geex-content__review__single__btn success-color">Publish <i class="uil-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <!-- Add more slides as needed -->
                    </div>
                </div>
            </div>
        </div>
        <!-- TESTIMONIALS END -->
    </div>

    <div class="geex-content__widget">

        <!-- SERVER STATUS CHART START -->
        <div class="geex-content__widget__single">
            <div class="geex-content__widget__single__header">
                <h4 class="geex-content__widget__single__title">Server Status</h4>
                <p class="geex-content__widget__single__subtitle">Consectetur et quo dolor vero.</p>
            </div>
            <div class="geex-content__widget__single__content">
                <div id="stack-chart"></div>
            </div>
        </div>
        <!-- SERVER STATUS CHART END -->
        <!-- RECENT PROBLEMS START -->
        <div class="geex-content__widget__single">
            <div class="geex-content__widget__single__header">
                <h4 class="geex-content__widget__single__title">Recent Problems</h4>
                <p class="geex-content__widget__single__subtitle">Maiores dicta atque dolorem temporibus</p>
            </div>
            <div class="geex-content__widget__single__content">
                <div class="geex-content__widget__single__cta mb-30">
                    <div class="geex-content__widget__single__cta__img">
                        <img src="<?= $this->Url->assetUrl('assets/img/icon/google.svg') ?>" alt="google" />
                    </div>
                    <div class="geex-content__widget__single__cta__text">
                        <h4 class="geex-content__widget__single__cta__title">Google</h4>
                        <a href="https://www.google.com" class="geex-content__widget__single__cta__link">https://www.google.com</a>
                    </div>
                    <div class="geex-content__widget__single__cta__status">
                        <a href="#" class="geex-content__widget__single__cta__btn danger-bg">Down</a>
                    </div>
                </div>
                <div class="geex-content__widget__single__cta mb-30">
                    <div class="geex-content__widget__single__cta__img">
                        <img src="<?= $this->Url->assetUrl('assets/img/icon/facebook.svg') ?>" alt="google" />
                    </div>
                    <div class="geex-content__widget__single__cta__text">
                        <h4 class="geex-content__widget__single__cta__title">Facebook</h4>
                        <a href="https://www.google.com" class="geex-content__widget__single__cta__link">https://www.facebook.com</a>
                    </div>
                    <div class="geex-content__widget__single__cta__status">
                        <a href="#" class="geex-content__widget__single__cta__btn success-bg">Stable</a>
                    </div>
                </div>
                <div class="geex-content__widget__single__cta">
                    <div class="geex-content__widget__single__cta__img">
                        <img src="<?= $this->Url->assetUrl('assets/img/icon/youtube.svg') ?>" alt="google" />
                    </div>
                    <div class="geex-content__widget__single__cta__text">
                        <h4 class="geex-content__widget__single__cta__title">Youtube</h4>
                        <a href="https://www.google.com" class="geex-content__widget__single__cta__link">https://www.youtube.com</a>
                    </div>
                    <div class="geex-content__widget__single__cta__status">
                        <a href="#" class="geex-content__widget__single__cta__btn warning-bg">Warning</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- RECENT PROBLEMS END -->
        <!-- ACTIVITY SECTION START -->
        <div class="geex-content__widget__single">
            <div class="geex-content__widget__single__header">
                <h4 class="geex-content__widget__single__title">Latest Activity</h4>
                <p class="geex-content__widget__single__subtitle">Sit et tempora dicta omnis ab quia quo quo.</p>
            </div>
            <div class="geex-content__widget__single__content">
                <div class="geex-content__widget__single__timeline">
                    <div class="geex-content__widget__single__timeline__content">
                        <h4 class="geex-content__widget__single__timeline__content__title">January 2nd, 04:35 AM</h4>
                        <p class="geex-content__widget__single__timeline__content__subtitle">Illum omnis quo illum nisi. Nesciunt est accusamus. Blanditiis nisi quae eum nisi similique. Modi consequuntur totam</p>
                    </div>
                </div>
                <div class="geex-content__widget__single__timeline">
                    <div class="geex-content__widget__single__timeline__content">
                        <h4 class="geex-content__widget__single__timeline__content__title">January 4th, 06:19 AM</h4>
                        <p class="geex-content__widget__single__timeline__content__subtitle">Corrupti unde qui molestiae labore ad adipisci veniam perspiciatis quasi. Quae labore vel.</p>
                    </div>
                </div>
                <div class="geex-content__widget__single__timeline">
                    <div class="geex-content__widget__single__timeline__content">
                        <h4 class="geex-content__widget__single__timeline__content__title">January 5th, 12:34 AM</h4>
                        <p class="geex-content__widget__single__timeline__content__subtitle">Maiores doloribus qui. Repellat accusamus minima ipsa ipsam aut debitis quis sit voluptates. Amet necessitatibus non minus quaerat et quis.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- ACTIVITY SECTION END -->

    </div>
</div>

