/**
 * @property {HTMLElement} element
 * @property {{y: number, r: number, variable: boolean}} options
 */
export class Observer {


    /**
     * @param {HTMLElement} element
     * @param {{root: HTMLElement, threshold: number, rootMargin: String }} options
     * @param {String} className
     */
    constructor(element, options = {}, className = '') {
        this.element = element;
        this.options = options;
        this.className = className;
        this.class = this.getClass();
        this.onIntersection = this.onIntersection.bind(this);

        const observer = new IntersectionObserver(this.onIntersection, this.options)
        observer.observe(this.element)
    }


    /**
     * @return String
     */
    getClass() {
        if (this.className !== "") {
            return this.className
        }
        else if (this.element.dataset.observer !== "")
            return this.element.dataset.observer;
        else {
            return "intersecting"
        }
    }


    onIntersection(entries) {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add(this.class)
            } else {
                entry.target.classList.remove(this.class)
            }
        })
    }
}