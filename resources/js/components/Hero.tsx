import { MoveRight, PhoneCall } from 'lucide-react';
import { Button } from './ui/button';

export const Hero = () => (
    <div className="w-full">
        <div className="container mx-auto">
            <div className="flex flex-col items-center justify-center gap-8 py-20 lg:py-40">
                <div>
                    <Button variant="secondary" size="sm" className="gap-4">
                        Read our launch article <MoveRight className="h-4 w-4" />
                    </Button>
                </div>
                <div className="flex flex-col gap-4">
                    <h1 className="font-regular max-w-2xl text-center text-5xl tracking-tighter md:text-7xl">This is the start of something new</h1>
                    <p className="text-muted-foreground max-w-2xl text-center text-lg leading-relaxed tracking-tight md:text-xl">
                        Managing a small business today is already tough. Avoid further complications by ditching outdated, tedious trade methods. Our
                        goal is to streamline SMB trade, making it easier and faster than ever.
                    </p>
                </div>
                <div className="flex flex-row gap-3">
                    <Button size="lg" className="gap-4" variant="outline">
                        Jump on a call <PhoneCall className="h-4 w-4" />
                    </Button>
                    <Button size="lg" className="gap-4">
                        Sign up here <MoveRight className="h-4 w-4" />
                    </Button>
                </div>
            </div>
        </div>
    </div>
);
