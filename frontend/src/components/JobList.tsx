import JobCard from './JobCard'
import styles from './JobList.module.css'
import type { Job } from '../data/careers'

type JobListProps = {
  jobs: Job[]
}

function JobList({ jobs }: JobListProps) {
  return (
    <div className={styles.container}>
      {jobs.map((job) => (
        <JobCard
          key={job.id}
          category={job.category}
          description={job.description}
          tags={{ location: job.location, type: job.type }}
          title={job.title}
        />
      ))}
    </div>
  )
}

export default JobList
