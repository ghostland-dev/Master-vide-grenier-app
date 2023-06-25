#!/bin/bash

# Function to deploy an environment
deploy_environment() {
  environment=$1
  compose_file="docker-compose.$environment.yml"

  echo "Deploying $environment environment..."


  cd ./$environment

  docker-compose -f $compose_file down
  docker-compose -f $compose_file up -d --build
  cd ..
  echo "Deployment of $environment environment completed successfully."
  echo
}

# Deploy dev environment
deploy_environment 'DEV'

# Deploy preprod environment
deploy_environment 'PREPROD'

# Deploy prod environment
deploy_environment 'PROD'
