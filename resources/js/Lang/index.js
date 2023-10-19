import { createI18n } from "vue-i18n";
import zh_CN from "./zh_CN";
import en_US from "./en_US";
import {currentLanguage} from "@/config";

const i18n = createI18n({
    locale: localStorage[currentLanguage] ?? 'en_US',
    legacy: false,
    globalInjection: true,
    messages: {
        zh_CN,
        en_US,
    },
});

export default i18n;
