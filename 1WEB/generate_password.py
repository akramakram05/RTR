import random, string, sys

mode = sys.argv[1]  # 'alpha', 'alnum', 'special'
chars = string.ascii_letters
if mode in ['alnum','special']:
    chars += string.digits
if mode == 'special':
    chars += "!@#$%^&*()-_=+[]{}"

def gen(n=12):
    return ''.join(random.choice(chars) for _ in range(n))

if __name__=='__main__':
    print(gen())
