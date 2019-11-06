Steps to execute the server configuration, couchdb configuration and deployment
1. Generate your ssh-key named 'walk.pem' and place it in the Ansible directory.
2. Execute run-config-server.sh file using the key generated in the unimelb research cloud.
3. Once the instances are created, edit/create a hosts file in the following example format

```
[webserver]
45.113.232.171
[procserver]
115.146.92.63
[dbserver]
115.146.93.227
115.146.93.213
115.146.92.54
```
where instance walk_web ip address is stored under [webserver], walk_pro under [procserver] and walk_1, walk_2 & walk_3 stored under [dbserver].

3. Execute run-config-env-couchdb-deploy.sh file. 
4. The webapplication is up and running. Using <walk_web ip address>/web for example 45.113.232.171/web or http://45.113.232.171/web
