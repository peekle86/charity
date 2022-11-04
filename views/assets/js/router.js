import VueRouter from "vue-router";


import Home from '@/assets/js/templates/Home.vue';
import Search from '@/assets/js/templates/Search.vue';
import PageView from '@/assets/js/templates/PageView.vue';
import ChangeOrg from '@/assets/js/templates/ChangeOrg.vue';
import Login from '@/assets/js/templates/admin/Login.vue';
import Dashboard from '@/assets/js/templates/admin/Dashboard.vue';

import Organizations from '@/assets/js/templates/admin/Organizations.vue';
import OrganizationsForm from '@/assets/js/templates/admin/OrganizationsForm.vue';

import Categories from '@/assets/js/templates/admin/Categories.vue';
import CategoriesForm from '@/assets/js/templates/admin/CategoriesForm.vue';

import Pages from '@/assets/js/templates/admin/Pages.vue';
import PagesForm from '@/assets/js/templates/admin/PagesForm.vue';


import Langs from '@/assets/js/templates/admin/Langs.vue';
import LangsForm from '@/assets/js/templates/admin/LangsForm.vue';

import Ads from '@/assets/js/templates/admin/Ads.vue';
import AdsForm from '@/assets/js/templates/admin/AdsForm.vue';

import Partners from '@/assets/js/templates/admin/Partners.vue';
import PartnersForm from '@/assets/js/templates/admin/PartnersForm.vue';

import Settings from '@/assets/js/templates/admin/Settings.vue';


import Users from '@/assets/js/templates/admin/Users.vue';
import UsersForm from '@/assets/js/templates/admin/UsersForm.vue';


const routes = [
    { path: "/", component: Home },
    { path: "/change", component: ChangeOrg },
    { path: "/reed/:id", component: PageView },
    { path: "/search", component: Search },

    { path: "/usr", component: Users },
    { path: "/usr/new", component: UsersForm },
    { path: "/usr/edit/:id", component: UsersForm },

    { path: "/dashboard", component: Dashboard },

    { path: "/orgs", component: Organizations },
    { path: "/orgs/new", component: OrganizationsForm },
    { path: "/orgs/edit/:id", component: OrganizationsForm },


    { path: "/pgs", component: Pages },
    { path: "/pgs/new", component: PagesForm },
    { path: "/pgs/edit/:id", component: PagesForm },


    { path: "/lng", component: Langs },
    { path: "/lng/new", component: LangsForm },
    { path: "/lng/edit/:id", component: LangsForm },


    { path: "/ads", component: Ads },
    { path: "/ads/new", component: AdsForm },
    { path: "/ads/edit/:id", component: AdsForm },

    { path: "/prtns", component: Partners },
    { path: "/prtns/new", component: PartnersForm },
    { path: "/prtns/edit/:id", component: PartnersForm },

    { path: "/cats", component: Categories },
    { path: "/cats/new", component: CategoriesForm },
    { path: "/cats/edit/:id", component: CategoriesForm },



    { path: "/setup", component: Settings },
    { path: "/login", component: Login },
];

const router = new VueRouter({
    mode: "history",
    routes: routes
});
export default router;
