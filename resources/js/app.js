import './bootstrap';
import '../css/app.css';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('localeSwitcher', (locales) => ({
    open: false,
    locales: locales.map(locale => ({
        ...locale,
        image: locale.image?.startsWith('http') ? locale.image : `/${locale.image}`,
    })),

    toggle() {
        this.open = !this.open;
    },

    setLocale({ code }) {
        const path = window.location.pathname !== '/' ? window.location.pathname : '';
        window.location.href = `${path}?lang=${code}`;
    }
}));

Alpine.data('localeForm', (translations, defaultLocaleId) => ({
    selectedLocale: parseInt(defaultLocaleId),
    fields: {},

    init() {
        if (translations.length > 0) {
            Object.keys(translations[0]).forEach(key => {
                this.fields[key] = '';
            });
        }

        this.loadTranslation(this.selectedLocale);
    },

    setLocale(localeId) {
        this.selectedLocale = parseInt(localeId);
        this.loadTranslation(localeId);
    },

    loadTranslation(localeId) {
        const translation = translations.find(t => t.locale_id === Number(localeId));

        Object.keys(this.fields).forEach(field => {
            this.fields[field] = translation?.[field] || '';
        });
    }
}));

Alpine.start();