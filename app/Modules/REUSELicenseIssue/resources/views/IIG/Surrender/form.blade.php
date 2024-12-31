<style>
    .wizard > .steps > ul > li {
        width: 25% !important;
    }
    .wizard > .content {
        overflow: visible;
    }

    .wizard > .actions {
        width: 70% !important;
    }

    .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
        background: #027DB4;
        color: #fff;
        cursor: default;
    }
    .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
        background: #027DB4;
        color: #fff;
    }
    .wizard > .steps .disabled a, .wizard > .steps .disabled a:hover, .wizard > .steps .disabled a:active {
        background: #F2F2F2;
        color: #028FCA;
        cursor: default;
    }
    .card-header {
        background: #1C9D50 !important;
        color: #ffffff;
    }
    .wizard > .steps > ul > li {
        width: 50% !important;
    }
    @media (max-width: 480px) {
        .wizard > .actions {
            width: 55% !important;
            position: inherit;
        }
        .wizard > .content > .body label {
            margin-top: .5em;
            margin-bottom: 0;
        }
    }
</style>

<div class="col-md-12" id="inputForm">
    <div id="fetchedData">
        @includeIf("REUSELicenseIssue::IIG.Surrender.search-blank")
    </div>
</div>



