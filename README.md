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
1. Create ECR
2. Create CodeCommit Repo
3. Push Docker Image to ECR
4. Push Code to CodeCommit
5. Create ECS

## How would you approach scaling this app?
kubernetes

