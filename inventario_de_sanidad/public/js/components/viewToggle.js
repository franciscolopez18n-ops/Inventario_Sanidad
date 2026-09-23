export class ViewToggle {
    #views;

    constructor(views) {
        this.#views = views;
    }

    init() {
        this.#views.forEach((view, index) => {
            view.button.addEventListener("click", (e) => {
                e.preventDefault();
                this.activate(index);
            });
        });

        return this;
    }

    activate(activeIndex) {
        this.#views.forEach((view, index) => {
            let isActive = index === activeIndex;
            view.container.classList.toggle("hidden", !isActive);
            view.button.classList.toggle("active", isActive);
        });
    }
}