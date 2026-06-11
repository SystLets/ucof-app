db = db.getSiblingDB('ucof');
db.createCollection('_settings');

db.createUser({
  user: 'DB-ADMIN-USERNAME',
  pwd: 'DB-ADMIN-USER-PASSWORD',
  roles: [
    {
      role: 'readWrite',
      db: 'ucof',
    },
  ],
});
