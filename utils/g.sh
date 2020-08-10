git branch -d master
git push origin --delete master
git checkout v20
git branch -m master
git push origin -u master
git push origin --delete v20
