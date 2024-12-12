import { Admin } from "../page/admin/admin";
import { Analytics } from "../page/admin/analytics/page-analytics";
import { ReportClient } from "../page/admin/feedback/page";
import { Help } from "../page/admin/help/help";
import { myFilial } from "../page/admin/myfilial/myfilial";
import { Reviews } from "../page/admin/reviews/admin-reviews";
import { SettingAdmin } from "../page/admin/settings/setting";
import { Login } from "../page/Auth/Login";
import { Feedback } from "../page/feedback/feedback";
import { Home } from "../page/start/home";
import { Companies } from "../page/technical/all-client/Companies";
import { TechClient } from "../page/technical/client/TechClient";
import { Logs } from "../page/technical/logs";
import { NewProject } from "../page/technical/new-project";
import { NewsMy } from "../page/technical/news/news";
import { Download } from "../page/technical/platform/Download";
import { Platform } from "../page/technical/platform/platform";
import { Tech } from "../page/technical/tech-cab";
import { Setting } from "../page/technical/tech-cab-setting";
import { upload } from "../page/technical/upload-parser";



export let PAGES:any= {
    'tech':Tech,
    'login':Login,
    'admin':Admin,
    "home":Home,

    "tech/plat/parser":Platform,
    "tech/new-project":NewProject,
    "tech/companies":Companies,
    "tech/download":Download,
    "tech/setting":Setting,
    "tech/upload":upload,
    "tech/logs":Logs,
    "tech/client":TechClient,
    "tech/news":NewsMy,

    "admin/reviews":Reviews,
    "feedback":Feedback,
    "admin/request":ReportClient,

    "admin/setting":SettingAdmin,
    "admin/myfilial":myFilial,
    "admin/analytics":Analytics,
    "admin/help":Help
}