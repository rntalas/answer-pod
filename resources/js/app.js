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

Alpine.data('imageUpload', (message = '', multiple = false, max = 5) => ({
    open: false,
    previews: [],
    dragover: false,
    draggedIndex: null,
    draggedOverIndex: null,

    pick(e) {
        this.addFiles(Array.from(e.target.files));
    },

    handleDrop(e) {
        e.preventDefault();
        this.dragover = false;
        this.addFiles(Array.from(e.dataTransfer.files));
    },

    addFiles(files) {
        files.filter(f => this.validateFile(f))
            .forEach(file => {
                if (this.isDuplicate(file)) return alert(`"${file.name}" already added.`);
                if (this.previews.length >= max) return alert(`Maximum ${max} images allowed.`);

                this.previews.push({
                    url: URL.createObjectURL(file),
                    name: file.name,
                    file: file
                });
            });

        this.updateFileInput();
    },

    isDuplicate(file) {
        return this.previews.some(p => p.file.name === file.name && p.file.size === file.size);
    },

    removeImage(index) {
        URL.revokeObjectURL(this.previews[index].url);
        this.previews.splice(index, 1);
        this.updateFileInput();
    },

    startDrag(index) {
        this.draggedIndex = index;
    },

    onDragOver(index) {
        if (this.draggedIndex === null || this.draggedIndex === index) return;

        const item = this.previews.splice(this.draggedIndex, 1)[0];
        this.previews.splice(index, 0, item);
        this.draggedIndex = index;
    },

    endDrag() {
        this.draggedIndex = null;
        this.updateFileInput();
    },

    updateFileInput() {
        const dt = new DataTransfer();
        this.previews.forEach(item => dt.items.add(item.file));
        this.$refs.fileInput.files = dt.files;
    },

    validateFile(file) {
        const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'];
        const maxSize = 2 * 1024 * 1024;

        if (!validTypes.includes(file.type)) {
            alert(message || 'Invalid file type.');
            return false;
        }
        if (file.size > maxSize) {
            alert(`"${file.name}" exceeds 2MB.`);
            return false;
        }
        return true;
    },

    destroy() {
        this.previews.forEach(img => URL.revokeObjectURL(img.url));
    }
}));

Alpine.start();
