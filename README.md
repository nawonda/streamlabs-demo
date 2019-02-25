# streamlabs-demo

index.php ---> login page + user info page.

detail.php ---> streamer detail page + websocket client + webhook

api.php ---> twitch api connection(followed, users, follows/channels)

_access.txt ---> save client access token

ps: websocket and webhook handler are in another node server


## How would you deploy the above on AWS? 

Prerequisites
- Docker
- AWS account
- AWS CLI

Deploy to AWS
step1. Create ECR
step2. Create CodeCommit Repo
step3. Push Docker Image to ECR
step4. Push Code to CodeCommit
step5. Create ECS

## How would you approach scaling this app?
kubernetes

