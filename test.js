const CrudDSL = require('./dsl');

const crud = new CrudDSL();

// Create
crud.create({ id: 1, name: 'Item 1' });
crud.create({ id: 2, name: 'Item 2' });

// Read
console.log('Read Item 1:', crud.read(0));

// Update
crud.update(0, { id: 1, name: 'Updated Item 1' });
console.log('Updated Item 1:', crud.read(0));

// Delete
crud.delete(1);
console.log('List after delete:', crud.list());
