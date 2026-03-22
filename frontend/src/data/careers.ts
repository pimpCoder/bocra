export type Job = {
  id: number
  category: string
  title: string
  description: string
  location: string
  type: string
}

export const jobs: Job[] = [
  {
    id: 1,
    category: 'Regulation',
    title: 'Licensing Officer',
    description:
      'Support licensing evaluations, stakeholder engagement, and compliance processes across communication services.',
    location: 'Gaborone',
    type: 'Full-time',
  },
  {
    id: 2,
    category: 'Legal',
    title: 'Legal and Policy Analyst',
    description:
      'Provide legal and policy research to strengthen regulatory frameworks and support sector governance initiatives.',
    location: 'Gaborone',
    type: 'Full-time',
  },
  {
    id: 3,
    category: 'Compliance',
    title: 'Compliance Inspector',
    description:
      'Conduct monitoring and inspections to ensure adherence to standards, license terms, and consumer protection requirements.',
    location: 'Francistown',
    type: 'Full-time',
  },
]
