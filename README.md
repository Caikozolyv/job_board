# Job board

This public project allows to follow jobs applications

## [Docker Installation](#docker) / [WSL Installation](#wsl)

## Regular Installation

Clone the project using https or ssh

```bash
git clone git@github.com:Caikozolyv/job_board.git
```
Go to the project root and install packages with composer
```bash
cd job_board
composer install
```
Install dependencies using yarn or npm
```bash
npm install
yarn install
```
Build the assets using npm or yarn
```bash
npm run dev
yarn encore dev
```
Finally launch a server using Symfony
```bash
symfony serve
```

## <a name="docker"></a> Docker installation
Clone the project using https or ssh

```bash
git clone git@github.com:Caikozolyv/job_board.git
```
Install make
```bash
sudo apt install make
```
Build and start the containers
```bash
make init
```
Go to `localhost:80`
## <a name="wsl"></a> Using WLS
Use WLS branch and follow docker installation.\
The only change is running `yarn install` as root.