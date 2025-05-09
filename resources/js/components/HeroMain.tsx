import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { motion } from 'framer-motion';
import { MoveRight, PhoneCall } from 'lucide-react';
import React from 'react';

export const HeroMain: React.FC = () => {
    const [move, setMove] = React.useState(false);

    return (
        <div className="w-full bg-gray-100 px-10 py-20 lg:py-20">
            <div className="container mx-auto px-4">
                <div className="grid grid-cols-1 items-center gap-8 lg:grid-cols-2">
                    {/* Text Section */}
                    <div className="flex flex-col gap-4">
                        <div>
                            <Badge variant="outline">We&apos;re live!</Badge>
                        </div>
                        <div className="flex flex-col gap-4">
                            <h1 className="font-regular max-w-lg text-left text-5xl tracking-tighter md:text-7xl">This is the start of something!</h1>
                            <p className="text-muted-foreground max-w-md text-left text-xl leading-relaxed tracking-tight">
                                Managing a small business today is already tough. Avoid further complications by ditching outdated, tedious trade
                                methods. Our goal is to streamline SMB trade, making it easier and faster than ever.
                            </p>
                        </div>
                        <div className="flex flex-row gap-4">
                            <Button size="lg" className="gap-4" variant="outline">
                                Jump on a call <PhoneCall className="h-4 w-4" />
                            </Button>
                            <Button size="lg" className="gap-4">
                                Sign up here <MoveRight className="h-4 w-4" />
                            </Button>
                        </div>
                    </div>

                    {/* Image Section */}
                    <div className="flex flex-col items-center gap-6">
                        {/* <img src="/images/bn1.png" className="w-128 rounded-lg" alt="Banner 1" /> */}
                        {/* <motion.div animate={{ x: move ? 50 : -50 }} transition={{ repeat: Infinity, duration: 5 }}>
                            <img src="/images/bn1.png" className="w-128 rounded-lg" alt="Animated Banner 1" />
                        </motion.div> */}
                        {/* <motion.div
                            // whileHover={{ scale: 1.3 }}
                        >
                            <img src="/images/bn1.png" className="w-128 rounded-lg" alt="Animated Banner 1" />
                        </motion.div> */}
                        <motion.div
                            animate={{ x: move ? 50 : -50 }}
                            transition={{ repeat: Infinity, duration: 5 }}
                            // transition={{ type: 'tween', duration: 5 }}
                            // transition={{ type: 'spring', bonuce: 5 }}
                            // transition={{ type: 'inertia', velocity: 5 }}
                            onLoad={() => setMove(!move)}
                            onMouseLeave={() => setMove(!move)}
                            // onClick={() => setMove(!move)}
                            className="z-0 cursor-pointer"
                        >
                            <img src="/images/bn3.png" className="w-128 rounded-lg" alt="Animated Banner 3" />
                        </motion.div>
                    </div>
                </div>
            </div>
        </div>
    );
};
