#! /bin/bash

sudo git checkout main
sudo git add .
sudo git commit -m "Testing deployment"
sudo git push
sudo git pull >>  pull_data.txt

echo "success";
