. ./unimelb-walkabilityAndObesity-openrc.sh; ansible-playbook --ask-become-pass config-env-couchdb-deploy.yaml -i hosts --ssh-common-args='-o StrictHostKeyChecking=no'