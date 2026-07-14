<div class="flex-lg-row-fluid">
    <style>
        /* SKELETON LOADING */
        .skeleton-box {
            display: inline-block;
            height: 1em;
            position: relative;
            overflow: hidden;
            background-color: var(--bs-app-skeleton-color);
        }
        .skeleton-box::after {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            transform: translateX(-100%);
            background-image: var(--bs-app-skeleton-gradient);
            -webkit-animation: shimmer 2s infinite;
                    animation: shimmer 2s infinite;
            content: "";
        }
        .blog-post__headline {
            font-size: 1.25em;
            font-weight: bold;
        }
        .blog-post__meta {
            font-size: 0.85em;
            color: #6b6b6b;
        }
        .o-media {
            display: flex;
        }
        .o-media__body {
            flex-grow: 1;
        }
        .o-vertical-spacing > * + * {
            margin-top: 0.75em;
        }
        .o-vertical-spacing--l > * + * {
            margin-top: 2em;
        }
        @-webkit-keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }
        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }
    </style>
    <div class="o-vertical-spacing o-vertical-spacing--l my-3">
        <div class="blog-post o-media">
            <div class="o-media__body">
                <div class="o-vertical-spacing">
                    <h3 class="blog-post__headline">
                        <span class="skeleton-box" style="width: 100%; height: 30vh; border-radius: 5px;"></span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>

