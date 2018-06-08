import os, requests, argparse, json

class RedmineUpdater(object):
    '''Updates redmine ticket with inputted message
    usage: 
        python redmine_updater.py --help
        python redmine_updater.py --message 'hello world' --redmine 1234
    '''
    __kachan_api_token = '0413d121aacaed6e46f8163efa833adc8ffb9a71'
    
    def __init__(self, message, redmine_ticket):
        self.message = message
        self.redmine_ticket = redmine_ticket

    def send(self):
        url='https://redmine.rarejob.ph/issues/{0}.json'.format(self.redmine_ticket)
        headers={
            'Content-Type': 'application/json',
            'X-Redmine-API-Key': self.__kachan_api_token
        }
        data=json.dumps({
            'issue': {
                'notes': self.message
            }
        })
        print requests.put(url, headers=headers, data=data)


if __name__=='__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--message', help='message to update the redmine ticket with', required=True)
    parser.add_argument('--redmine', help='redmine ticket number', required=True)
    args = parser.parse_args()

    RedmineUpdater(args.message, args.redmine).send()