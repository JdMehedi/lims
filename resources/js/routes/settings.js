// import ActRulesList from '../components/settings/act-rules/ActRulesList'
// import ActRulesCreate from '../components/settings/act-rules/ActRulesCreate'
// import ActRulesEdit from '../components/settings/act-rules/ActRulesEdit'

// import BankList from "../components/settings/bank/BankList";
// import BankCreate from "../components/settings/bank/BankCreate";
// import BankEdit from "../components/settings/bank/BankEdit";
// import BranchList from "../components/settings/branch/BranchList";
// import BranchCreate from "../components/settings/branch/BranchCreate";
// import BranchEdit from "../components/settings/branch/BranchEdit";

// import TermsConditionList from '../components/settings/terms-condition/TermsConditionList.vue';
// import TermsConditionCreate from '../components/settings/terms-condition/TermsConditionCreate.vue';
// import TermsConditionEdit from '../components/settings/terms-condition/TermsConditionEdit.vue';

// import ServiceDetailsList from '../components/settings/service-details/ServiceDetailsList.vue';
// import ServiceDetailsCreate from '../components/settings/service-details/ServiceDetailsCreate.vue';
// import ServiceDetailsEdit from '../components/settings/service-details/ServiceDetailsEdit.vue';


let routes = [
    //eligibility
    {
        path: '/eligible-area-list',
        component: () => import(/* webpackChunkName: "Area" */ '../components/settings/area/EligibilityAreaList'),
        name: 'Eligible AreaList'
    },
    //area
    {
        path: '/area-list',
        component: () => import(/* webpackChunkName: "Area" */ '../components/settings/area/AreaList'),
        name: 'AreaList'
    },
    {
        path: '/area/create',
        component: () => import(/* webpackChunkName: "Area" */ '../components/settings/area/AreaCreate'),
        name: 'AreaCreate'
    },
    {
        path: '/area/edit/:id',
        component: () => import(/* webpackChunkName: "Area" */ '../components/settings/area/AreaEdit'),
        name: 'AreaEdit'
    },
    // acts and rules
    /*{
        path: '/act-rules',
        component: ActRulesList,
        name: 'ActRulesList'
    },
    {
        path: '/create-act-rules',
        component:  ActRulesCreate,
        name: 'ActRulesCreate'
    },
    {
        path: '/edit-act-rules/:id',
        component: ActRulesEdit,
        name: 'ActRulesEdit'
    },*/
    // bank
    {
        path: '/bank-list',
        component: () => import(/* webpackChunkName: "Bank" */ '../components/settings/bank/BankList'),
        name: 'BankList'
    },
    {
        path: '/bank/create',
        component: () => import(/* webpackChunkName: "Bank" */ '../components/settings/bank/BankCreate'),
        name: 'BankCreate'
    },
    {
        path: '/bank/edit/:id',
        component: () => import(/* webpackChunkName: "Bank" */ '../components/settings/bank/BankEdit'),
        name: 'BankEdit'
    },
    // branch
    {
        path: '/branch-list',
        component: () => import(/* webpackChunkName: "BankBranch" */ '../components/settings/branch/BranchList'),
        name: 'BranchList'
    },
    {
        path: '/branch/create',
        component: () => import(/* webpackChunkName: "BankBranch" */ '../components/settings/branch/BranchCreate'),
        name: 'BranchCreate'
    },
    {
        path: '/branch/edit/:id',
        component: () => import(/* webpackChunkName: "BankBranch" */ '../components/settings/branch/BranchEdit'),
        name: 'BranchEdit'
    },
    // notice
    {
        path: '/notice-list',
        component: () => import(/* webpackChunkName: "Notice" */ '../components/settings/notice/NoticeList'),
        name: 'NoticeList'
    },
    {
        path: '/notice/create',
        component: () => import(/* webpackChunkName: "Notice" */ '../components/settings/notice/NoticeCreate'),
        name: 'NoticeCreate'
    },
    {
        path: '/notice/edit/:id',
        component: () => import(/* webpackChunkName: "Notice" */ '../components/settings/notice/NoticeEdit'),
        name: 'NoticeEdit'
    },
    //security
    {
        path: '/security',
        component: () => import(/* webpackChunkName: "Security" */ '../components/settings/security/SecurityList'),
        name: 'SecurityList'
    },
    {
        path: '/security/create',
        component: () => import(/* webpackChunkName: "Security" */ '../components/settings/security/SecurityCreate'),
        name: 'SecurityCreate'
    },
    {
        path: '/security/edit/:id',
        component: () => import(/* webpackChunkName: "Security" */ '../components/settings/security/SecurityEdit'),
        name: 'SecurityEdit'
    },
    //company info
    {
        path: '/company-info',
        component: () => import(/* webpackChunkName: "CompanyInfo" */ '../components/settings/company-info/CompanyInfoList'),
        name: 'CompanyInfoList'
    },
    //currency
    {
        path: '/currency-list',
        component: () => import(/* webpackChunkName: "Currency" */ '../components/settings/currency/CurrencyList'),
        name: 'CurrencyList'
    },
    {
        path: '/currency/create',
        component: () => import(/* webpackChunkName: "Currency" */ '../components/settings/currency/CurrencyCreate'),
        name: 'CurrencyCreate'
    },
    {
        path: '/currency/edit/:id',
        component: () => import(/* webpackChunkName: "Currency" */ '../components/settings/currency/CurrencyEdit'),
        name: 'CurrencyEdit'
    },
    // user type
    {
        path: '/user-type',
        component: () => import(/* webpackChunkName: "UserType" */ '../components/settings/user_type/UserTypeList'),
        name: 'UserTypeList'
    },
    {
        path: '/user_type/edit/:id',
        component: () => import(/* webpackChunkName: "UserType" */ '../components/settings/user_type/UserTypeEdit'),
        name: 'UserTypeEdit'
    },
    //pdf print request
    {
        path: '/pdf-print-requests',
        component: () => import(/* webpackChunkName: "PdfPrintRequests" */ '../components/settings/pdf-print-requests/PdfPrintRequestsList'),
        name: 'PdfPrintRequestsList'
    },
    {
        path: '/pdf-print-requests/verify/:id',
        component: () => import(/* webpackChunkName: "PdfPrintRequests" */ '../components/settings/pdf-print-requests/PdfPrintRequestsVerify'),
        name: 'PdfPrintRequestsVerify'
    },
    {
        path: '/pdf-print-requests/edit/:id',
        component: () => import(/* webpackChunkName: "PdfPrintRequests" */ '../components/settings/pdf-print-requests/PdfPrintRequestsEdit'),
        name: 'PdfPrintRequestsEdit'
    },
    // email and sms queue
    {
        path: '/email-sms-queue',
        component: () => import(/* webpackChunkName: "EmailSmsQueue" */ '../components/settings/email_sms_queue/EmailSmsQueueList'),
        name: 'EmailSmsQueueList'
    },
    {
        path: '/email-sms-queue/edit:id',
        component: () => import(/* webpackChunkName: "EmailSmsQueue" */ '../components/settings/email_sms_queue/EmailSmsQueueEdit'),
        name: 'EmailSmsQueueEdit'
    },
    //terms and conditions
    /* {
         path: '/terms-condition',
         component: TermsConditionList,
         name: 'TermsConditionList'
     },
     {
         path: '/terms-condition/create',
         component:  TermsConditionCreate,
         name: 'TermsConditionCreate'
     },
     {
         path: '/terms-condition/edit/:id',
         component: TermsConditionEdit,
         name: 'TermsConditionEdit'
     },*/
    //service-details
    /*   {
           path: '/service-details',
           component: ServiceDetailsList,
           name: 'ServiceDetailsList'
       },
       {
           path: '/service-details/create',
           component:  ServiceDetailsCreate,
           name: 'ServiceDetailsCreate'
       },
       {
           path: '/service-details/edit/:id',
           component: ServiceDetailsEdit,
           name: 'ServiceDetailsEdit'
       },*/

    // logo
    {
        path: '/edit-logo',
        component: () => import(/* webpackChunkName: "Logo" */ '../components/settings/logo_title/LogoEdit'),
        name: 'LogoEdit'
    },
    //home page slider
    {
        path: '/home-page/home-page-slider',
        component: () => import(/* webpackChunkName: "HomePageSlider" */ '../components/settings/home-page-slider/HomePageSliderList.vue'),
        name: 'HomePageSliderList'
    },
    {
        path: '/home-page/home-page-slider/create',
        component: () => import(/* webpackChunkName: "HomePageSlider" */ '../components/settings/home-page-slider/HomePageSliderCreate.vue'),
        name: 'HomePageSliderCreate'
    },
    {
        path: '/home-page/home-page-slider/edit/:id',
        component: () => import(/* webpackChunkName: "HomePageSlider" */ '../components/settings/home-page-slider/HomePageSliderEdit.vue'),
        name: 'HomePageSliderEdit'
    },
    // user manual
    {
        path: '/home-page/user-manual',
        component: () => import(/* webpackChunkName: "UserManual" */ '../components/settings/user-manual/UserManualList.vue'),
        name: 'UserManualList'
    },
    {
        path: '/home-page/user-manual/create',
        component: () => import(/* webpackChunkName: "UserManual" */ '../components/settings/user-manual/UserManualCreate.vue'),
        name: 'UserManualCreate'
    },
    {
        path: '/home-page/user-manual/edit/:id',
        component: () => import(/* webpackChunkName: "UserManual" */ '../components/settings/user-manual/UserManualEdit.vue'),
        name: 'UserManualEdit'
    },
    //home page content
    {
        path: '/home-page/home-page-content',
        component: () => import(/* webpackChunkName: "HomeContent" */ '../components/settings/home-page-content/HomeContentList.vue'),
        name: 'HomeContentList'
    },
    {
        path: '/home-page/home-page-content/create',
        component: () => import(/* webpackChunkName: "HomeContent" */ '../components/settings/home-page-content/HomeContentCreate.vue'),
        name: 'HomeContentCreate'
    },
    {
        path: '/home-page/home-page-content/edit/:id',
        component: () => import(/* webpackChunkName: "HomeContent" */ '../components/settings/home-page-content/HomeContentEdit.vue'),
        name: 'HomeContentEdit'
    },
    //industrial city
    {
        path: '/home-page/industrial-city',
        component: () => import(/* webpackChunkName: "IndustrialCity" */ '../components/settings/industrial-city/IndustrialCityList.vue'),
        name: 'IndustrialCityList'
    },
    {
        path: '/home-page/industrial-city/create',
        component: () => import(/* webpackChunkName: "IndustrialCity" */ '../components/settings/industrial-city/IndustrialCityCreate.vue'),
        name: 'IndustrialCityCreate'
    },
    {
        path: '/home-page/industrial-city/edit/:id',
        component: () => import(/* webpackChunkName: "IndustrialCity" */ '../components/settings/industrial-city/IndustrialCityEdit.vue'),
        name: 'IndustrialCityEdit'
    },
    {
        path: '/home-page/industrial-city/master-plan/edit/:id',
        component: () => import(/* webpackChunkName: "IndustrialCity" */ '../components/settings/industrial-city/MasterPlanEdit'),
        name: 'MasterPlanEdit'
    },
    //home page article
    {
        path: '/home-page/home-page-articles',
        component: () => import(/* webpackChunkName: "HomePageArticles" */ '../components/settings/home-page-articles/HomePageArticlesList.vue'),
        name: 'HomePageArticlesList'
    },
    {
        path: '/home-page/home-page-articles/create',
        component: () => import(/* webpackChunkName: "HomePageArticles" */ '../components/settings/home-page-articles/HomePageArticlesCreate.vue'),
        name: 'HomePageArticlesCreate'
    },
    {
        path: '/home-page/home-page-articles/edit/:id',
        component: () => import(/* webpackChunkName: "HomePageArticles" */ '../components/settings/home-page-articles/HomePageArticlesEdit.vue'),
        name: 'HomePageArticlesEdit'
    },

]

export default routes;
