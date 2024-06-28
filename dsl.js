class CrudDSL {
    constructor() {
        this.data = [];
    }

    create(item) {
        this.data.push(item);
        return item;
    }

    read(index) {
        return this.data[index];
    }

    update(index, newItem) {
        const oldItem = this.data[index];
        this.data[index] = newItem;
        return oldItem;
    }

    delete(index) {
        const item = this.data.splice(index, 1);
        return item;
    }

    list() {
        return this.data;
    }
}

module.exports = CrudDSL;
