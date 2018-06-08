import os, requests, argparse

class ChatWork(object):
    '''Sends messages to chatwork groups
    usage: 
        python chatwork_messages.py --help
        python chatwork_messages.py --message "message" --groupid 49333549
    '''
    __chatwork_token = '9aaefddccc402c115cf466a1f11f2e65'
    
    def __init__(self, message, chatwork_group_id):
        self.message = message
        self.chatwork_group_id = chatwork_group_id

    def send(self):
        url='https://api.chatwork.com/v2/rooms/{0}/messages'.format(self.chatwork_group_id)
        headers={'X-ChatWorkToken': self.__chatwork_token}
        message=self.message
        requests.post(url, headers=headers, data={'body': message})
        

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('--message', help='where to load the template ie. tutorial/template/release_reminder.txt', required=True)
    parser.add_argument('--groupid', help='chatwork groupId to sent the message to', required=True)
    args = parser.parse_args()

    ChatWork(args.message, args.groupid).send()
