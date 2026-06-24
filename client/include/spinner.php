<style>
@-webkit-keyframes spin {
    to {
        -webkit-transform: rotate(1080deg);
        transform: rotate(1080deg)
    }
}

@keyframes spin {
    to {
        -webkit-transform: rotate(1080deg);
        transform: rotate(1080deg)
    }
}

.spinner-wrapper {
    width: 26px;
    height: 26px;
    border-radius: 100%;
    display: inline-block;
    position: relative
}

.spinner {
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    border-radius: 100%;
    position: absolute;
    border: 3px solid currentColor;
    will-change: transition;
    -webkit-animation: spin 2666ms linear infinite;
    animation: spin 2666ms linear infinite;
    background-color: #fff
}

.spinner[data-options*=blue] {
    color: #4285f4
}

.spinner[data-options*=orange] {
    color: #ff6200
}

.spinner[data-options*=large] {
    font-size: 2em
}

.spinner[data-options*=xlarge] {
    font-size: 3em
}

.spinner::after,
.spinner::before {
    content: "";
    position: absolute;
    width: 1em;
    height: 22px;
    background-color: inherit
}

@-webkit-keyframes shape-shift-before {
    to {
        -webkit-transform: skewX(-45deg) translate(11px, -11px);
        transform: skewX(-45deg) translate(11px, -11px)
    }
}

@keyframes shape-shift-before {
    to {
        -webkit-transform: skewX(-45deg) translate(11px, -11px);
        transform: skewX(-45deg) translate(11px, -11px)
    }
}



.spinner-wrapper {
    display: block
}

.spinner-h1 {
    font-family: INGMe, arial, helvetica, sans-serif;
    font-size: 14px;
    font-weight: 400;
    height: 20px;
    justify-content: normal;
    line-height: 20.006px;
    margin-bottom: 16px;
    margin-top: 0;
    padding-top: 14px;
    position: relative;
    -webkit-font-smoothing: antialiased;
    -webkit-margin-after: 16px;
    -webkit-margin-before: 0;
    -webkit-margin-end: 0;
    -webkit-margin-start: 0;
    z-index: 10
}
</style>
<center>
    <div class="spinner-wrapper">
        <div class="spinner" data-options="orange" role="progressbar" aria-valuetext="Loading…"></div>
    </div>
    <h1 class="spinner-h1">
        Un momento...
    </h1>
</center>